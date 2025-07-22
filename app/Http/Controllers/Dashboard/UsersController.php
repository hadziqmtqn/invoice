<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SearchRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    use ApiResponse;

    public function index(SearchRequest $request): JsonResponse
    {
        try {
            $users = User::paginate($this->sort($request));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->paginateResponse($users->getCollection()->map(function (User $user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ];
        }), $users, Response::HTTP_OK);
    }
}
