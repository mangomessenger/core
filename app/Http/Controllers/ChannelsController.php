<?php

namespace App\Http\Controllers;

use App\Facades\ChatFacade;
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
            ChatFacade::channels()->create(
                $request->input('usernames') ?? [],
                $request->validated()
            ));
    }
}
