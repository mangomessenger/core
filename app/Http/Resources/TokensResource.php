<?php

namespace App\Http\Resources;

use App\Session;
use Illuminate\Http\Resources\Json\JsonResource;

class TokensResource extends JsonResource
{
    private string $accessToken;

    /**
     * TokenResource constructor.
     *
     * @param Session $resource
     * @param string $accessToken
     */
    public function __construct(Session $resource, string $accessToken)
    {
        parent::__construct($resource);

        $this->accessToken = $accessToken;
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
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refresh_token,
            'refresh_token_expires_in' => $this->expires_in->timestamp,
        ];
    }
}
