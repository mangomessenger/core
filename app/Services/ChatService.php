<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

abstract class ChatService
{
    /**
     * @param int $id
     * @return mixed
     */
    public function findByUserId(int $id): Collection
    {
        return $this->model->whereHas('members', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->get();
    }
}
