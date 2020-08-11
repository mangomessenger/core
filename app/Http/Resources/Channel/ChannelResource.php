<?php

namespace App\Http\Resources\Channel;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
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
            'tag' => $this->tag,
            'photo_url' => $this->photo_url,
            'verified' => (bool)$this->verified,
            'members_count' => (int)$this->members_count,
            'updated_at' => $this->updated_at->timestamp
        ];
    }
}
