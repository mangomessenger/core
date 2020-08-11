<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Chat\Channel\StoreChannelRequest;
use App\Http\Resources\Channel\ChannelResource;

class ChannelsController extends Controller
{
    /**
     * Creating chat instance
     *
     * @param StoreChannelRequest $request
     * @return ChannelResource
     */
    public function store(StoreChannelRequest $request)
    {
        return new ChannelResource(
            Chat::channels()->create(
                $request->input('usernames') ?? [],
                $request->validated()
            ));
    }
}
