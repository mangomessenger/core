<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthRequestResource extends JsonResource
{
    /**
     * AuthRequestResource constructor.
     *
     * @param $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

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
            'phone_code_hash' => $this->phone_code_hash,
            'is_new' => $this->is_new,
            'timeout' => $this->timeout,
        ];
    }
}
