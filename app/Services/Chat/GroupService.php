<?php

namespace App\Services\Chat;

use App\Models\Group;
use App\Services\ChatService;
use App\Services\User\UserService;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GroupService extends ChatService
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
        // Adding creator to members
        $users[] = auth()->user()->id;
        // Getting existing users
        $users = User::find($users)->pluck('id')->toArray();

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
