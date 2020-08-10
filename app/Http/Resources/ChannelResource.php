<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'creator' => new UserResource($this->creator),
            'tag' => $this->tag,
            'photo_url' => $this->photo_url,
            'verified' => $this->verified,
            'members_count' => (int)$this->members_count,
            'updated_at' => $this->updated_at->timestamp
        ];
    }
}
