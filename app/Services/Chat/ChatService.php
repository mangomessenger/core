<?php

namespace App\Services\Message;

use App\Chat;
use App\ChatMember;
use App\Exceptions\Message\ChatTypeInvalidException;
use App\Exceptions\Message\DestinationInvalidException;
use App\Message;
use App\Services\Auth\UserService;
use App\Services\ModelService;
use Illuminate\Support\Facades\DB;

class ChatService extends ModelService
{
    /**
     * Chat instance
     *
     * @var Chat
     */
    protected Chat $model;


    /**
     * MessageService constructor.
     *
     * @param Chat $chat
     */
    public function __construct(Chat $chat)
    {
        $this->model = $chat;

    }

    /**
     * @param int $id1
     * @param int $id2
     * @return mixed
     */
    public function findByUsers(int $id1, int $id2)
    {
        return $this->model
            ->where(function ($query) use ($id2, $id1) {
                $query->where('user1_id', $id1)
                    ->where('user2_id', $id2);
            })
            ->orWhere(function ($query) use ($id2, $id1) {
                $query->where('user1_id', $id2)
                    ->where('user2_id', $id1);
            })->first();
    }
}
