<?php

namespace App\Services\Chat;

use App\Models\DirectChat;
use App\Services\ModelService;
use App\Services\User\UserService;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DirectChatService

{
    /**
     * Chat instance
     *
     * @var DirectChat
     */
    protected DirectChat $model;

    /**
     * UserService instance
     *
     * @var UserService
     */
    protected UserService $userService;

    /**
     * MessageService constructor.
     *
     * @param DirectChat $chat
     * @param UserService $userService
     */
    public function __construct(DirectChat $chat, UserService $userService)
    {
        $this->model = $chat;
        $this->userService = $userService;
    }

    /**
     * @param string $username
     * @return DirectChat
     */
    public function create(string $username): DirectChat
    {
        // Getting existing user
        $user = User::firstWhere('username', $username);

        // Checking user exists or the same user
        if (is_null($user) || $user->is(auth()->user())) {
            abort(400);
        }
        // Creating array of users
        $users = [$user->id, auth()->user()->id];

        // Trying to retrieve already created chat
        $directChat = DirectChat::between($users)->first();

        // Return chat if it is already created
        if (!is_null($directChat)) return $directChat;

        // Creating chat & adding members
        return DB::transaction(function () use ($users) {
            $chat = $this->model->create([]);

            $chat->addMembers($users);

            return $chat;
        });
    }
}
