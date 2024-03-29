<?php

namespace App\Http\Resources\DirectChat;

use App\Http\Resources\User\UserCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DirectChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'members' => new UserCollection($this->members),
            'updated_at' => $this->updated_at->timestamp
        ];
    }
}
