<?php

namespace App\Services\Chat;

use App\Models\Channel;
use App\Services\User\UserService;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class ChannelService
{
    use ValidatesRequests, AuthorizesRequests;
    /**
     * Chat instance
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
        $users = User::find($users)->pluck('id');

        // Checking if less than 2 users
        if ($users->count() <= 1) {
            abort(400);
        }

        // Trying to retrieve already created chat
        $channel = Channel::whereHas('members', function (Builder $q) use ($users) {
            $q->select(DB::raw('count(chat_members.chat_id) AS count, chat_members.chat_id'))
                ->groupBy('chat_members.chat_id')
                ->having('count', count($users));
        })->first();

        // Return chat if it is already created
        if (!is_null($channel)) return $channel;

        // Creating chat & adding members
        return DB::transaction(function () use ($data, $users) {
            $channel = $this->model->create($data);

            $channel->addMembers($users);

            return $channel;
        });
    }
}
