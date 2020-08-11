<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'username' => $this->username,
            'bio' => $this->bio,
            'photo_url' => $this->photo_url,
            'verified' => (bool)$this->verified,
        ];
    }
}
