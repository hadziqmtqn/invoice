<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\NewUserRequest;
use App\Http\Requests\User\SearchRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    use ApiResponse;

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
        try {
            $users = User::filter($request)
                ->orderByDesc('created_at')
                ->paginate($this->sort($request));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->apiResponse('Internal server error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->paginateResponse($users->getCollection()->map(function (User $user) {
            return [
                'id' => $user->id,
                'avatar' => $user->avatar(),
                'name' => $user->name,
                'email' => $user->email
            ];
        }), $users, Response::HTTP_OK);
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
