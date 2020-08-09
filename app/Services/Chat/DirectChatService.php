<?php

namespace App\Services\Chat;

use App\Models\DirectChat;
use App\Services\User\UserService;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
     * @param array $users
     * @return DirectChat
     */
    public function create(array $users): DirectChat
    {
        // Getting existing users
        $users = User::find($users)->pluck('id');

        // Checking if only 2 users passed
        if ($users->count() !== 2) {
            abort(400);
        }

        // Trying to retrieve already created chat
        $directChat = DirectChat::whereHas('members', function (Builder $q) use ($users) {
            $q->select(DB::raw('count(chat_members.chat_id) AS count, chat_members.chat_id'))
                ->groupBy('chat_members.chat_id')
                ->having('count', count($users));
        })->first();

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
