<?php

namespace App\Services\Chat;

use App\Chat;
use App\Services\ModelService;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * @param int $id
     * @return mixed
     */
    public function getChats(int $id): Collection
    {
        return $this->model
            ->where('user1_id', $id)
            ->orWhere('user2_id', $id)
            ->get();
    }
}
