<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use ApiResponse;

    public function store(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                return $this->apiResponse('Login success', Auth::user()->createToken('web', ['*'], now()->addWeek())->plainTextToken, Response::HTTP_OK);
            }
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->apiResponse('Cek kembali akun anda', null, Response::HTTP_BAD_REQUEST);
    }

    public function logout(): JsonResponse
    {
        try {
            Auth::user()->tokens()->delete();

            return $this->apiResponse('Logout success', null, Response::HTTP_OK);
        }catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
