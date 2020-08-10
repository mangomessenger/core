<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    private string $accessToken;

    public function __construct($resource, string $accessToken)
    {
        parent::__construct($resource);

        $this->accessToken = $accessToken;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user' => new UserResource($this->user),
            'tokens' => [
                'access_token' => $this->accessToken,
                'refresh_token' => $this->refresh_token,
            ]
        ];
    }
}
