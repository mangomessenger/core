<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Chat\StoreChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Models\Channel;

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
                $request->input('user_ids') ?? [],
                $request->validated()
            ));
    }
}
