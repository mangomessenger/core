<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Services\User\UserService;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    /**
     * Instance of user service.
     *
     * @var UserService $userService
     */
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the specified resource.
     *
     * @param string $username
     * @return UserResource
     */
    public function show(string $username)
    {
        $user = $this->userService->firstWhere('username', $username);

        if (is_null($user)) abort(400);

        return new UserResource($user);
    }

    /**
     * Update the message
     *
     * @param UpdateUserRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        Chat::users()->update($id, $request->validated());

        return response()->noContent();
    }
}
