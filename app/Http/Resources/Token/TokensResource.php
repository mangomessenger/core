<?php

namespace App\Http\Resources\Token;

use App\Models\Session;
use Illuminate\Http\Resources\Json\JsonResource;

class TokensResource extends JsonResource
{
    /**
     * Passed access token to return
     *
     * @var string
     */
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
        ];
    }
}
