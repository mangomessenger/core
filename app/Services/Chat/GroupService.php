<?php

namespace App\Services\Chat;

use App\Models\Channel;
use App\Models\Group;
use App\Services\User\UserService;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GroupService
{
    /**
     * Chat instance
     *
     * @var Group
     */
    protected Group $model;

    /**
     * UserService instance
     *
     * @var UserService
     */
    protected UserService $userService;


    /**
     * MessageService constructor.
     *
     * @param Group $chat
     * @param UserService $userService
     */
    public function __construct(Group $chat, UserService $userService)
    {
        $this->model = $chat;
        $this->userService = $userService;
    }

    /**
     * @param array $users
     * @param array $data
     * @return Group
     */
    public function create(array $users, array $data): Group
    {
        // Getting existing users
        $users = User::find($users)->pluck('id')->toArray();

        // Checking if less than 2 users
        if (count($users) <= 1) {
            abort(400);
        }

        // Trying to retrieve already created chat
        $group = Group::whereHas('members', function (Builder $q) use ($users) {
            $q->select(DB::raw('count(chat_members.chat_id) AS count, chat_members.chat_id'))
                ->groupBy('chat_members.chat_id')
                ->having('count', count($users));
        })->first();

        // Return chat if it is already created
        if (!is_null($group)) return $group;

        // Creating chat & adding members
        return DB::transaction(function () use ($data, $users) {
            $group = $this->model->create([
                'title' => $data['title'],
                'creator_id' => auth()->user()->id,
                'photo_url' => null, // to be implemented
            ]);

            $group->addMembers($users);

            return $group;
        });
    }
}
