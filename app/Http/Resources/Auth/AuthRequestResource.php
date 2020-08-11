<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthRequestResource extends JsonResource
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
            'phone_number' => $this->phone_number,
            'country_code' => $this->country_code,
            'phone_code_hash' => $this->phone_code_hash,
            'is_new' => $this->is_new,
        ];
    }
}
