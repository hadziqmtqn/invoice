<?php

namespace App\Http\Controllers\Dashboard\BaseData;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    use ApiResponse, HandlesApiTryCatch;

    public function index(): JsonResponse
    {
        return $this->tryCatchApi(function () {
            $user = Auth::user();
            return $this->apiResponse('Get data success', [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar()
            ], Response::HTTP_OK);
        });
    }
}
