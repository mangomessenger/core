<?php

namespace App\Services\Chat;

use App\Models\Channel;
use App\Services\ChatService;
use App\Services\User\UserService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChannelService extends ChatService
{
    /**
     * ChatFacade instance
     *
     * @var Channel
     */
    protected Channel $model;

    /**
     * UserService instance
     *
     * @var UserService
     */
    protected UserService $userService;

    /**
     * MessageService constructor.
     *
     * @param Channel $chat
     * @param UserService $userService
     */
    public function __construct(Channel $chat, UserService $userService)
    {
        $this->model = $chat;
        $this->userService = $userService;
    }

    /**
     * @param array $users
     * @param array $data
     * @return Channel
     */
    public function create(array $users, array $data): Channel
    {
        // Getting existing users
        $users = User::whereIn('username', $users)->pluck('id')->toArray();

        // Adding creator to members
        $users[] = auth()->user()->id;

        // Creating chat & adding members
        return DB::transaction(function () use ($data, $users) {
            $channel = $this->model->create([
                'title' => $data['title'],
                'creator_id' => auth()->user()->id,
                'tag' => $data['tag'] ?? null,
                'photo_url' => null, // to be implemented
            ]);

            $channel->addMembers($users);

            return $channel;
        });
    }
}
