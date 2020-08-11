<?php

namespace App\Http\Resources\Group;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'title' => $this->title,
            'description' => $this->description,
            'creator' => new UserResource($this->creator),
            'members' => new UserCollection($this->members),
            'photo_url' => $this->photo_url,
            'members_count' => (int)$this->members_count,
            'updated_at' => $this->updated_at->timestamp
        ];
    }
}
