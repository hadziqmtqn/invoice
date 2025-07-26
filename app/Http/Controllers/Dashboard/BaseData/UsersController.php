<?php

namespace App\Http\Controllers\Dashboard\BaseData;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\NewUserRequest;
use App\Http\Requests\User\SearchRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponse;
use App\Traits\HandlesApiTryCatch;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    use ApiResponse, HandlesApiTryCatch;

    protected UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(SearchRequest $request): JsonResponse
    {
        return $this->tryCatchApi(function () use ($request) {
            $users = User::filter($request)
                ->orderByDesc('created_at')
                ->whereNotIn('id', [Auth::id()])
                ->paginate($this->sort($request));

            return $this->paginateResponse($users->getCollection()->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'avatar' => $user->avatar(),
                    'name' => $user->name,
                    'email' => $user->email
                ];
            }), $users, Response::HTTP_OK);
        });
    }

    public function store(NewUserRequest $request)
    {
        return $this->userService->store($request);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        return $this->userService->update($request, $user);
    }

    public function delete(User $user)
    {
        return $this->userService->delete($user);
    }
}
