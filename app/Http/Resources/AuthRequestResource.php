<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthRequestResource extends JsonResource
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
            'phone' => $this->phone,
            'country_code' => $this->country_code,
            'code' => $this->code,
            'is_new' => $this->is_new,
            'timeout' => $this->timeout,
        ];
    }
}
