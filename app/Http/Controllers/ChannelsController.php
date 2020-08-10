<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Chat\StoreChannelRequest;
use App\Models\Channel;

class ChannelsController extends Controller
{
    /**
     * Creating chat instance
     *
     * @param StoreChannelRequest $request
     * @return Channel
     */
    public function store(StoreChannelRequest $request)
    {
        return Chat::channels()->create($request->input('user_ids') ?? [], $request->validated());
    }
}
