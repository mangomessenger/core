<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\StoreChannelRequest;
use App\Models\Channel;
use App\Services\Chat\ChannelService;

class ChannelsController extends Controller
{
    /**
     * Instance of channel chat service.
     *
     * @var ChannelService $channelService
     */
    private ChannelService $channelService;

    /**
     * MessagesController constructor.
     *
     * @param ChannelService $channelService
     */
    public function __construct(
        ChannelService $channelService
    )
    {
        $this->channelService = $channelService;
    }

    /**
     * Creating chat instance
     *
     * @param StoreChannelRequest $request
     * @return Channel
     */
    public function store(StoreChannelRequest $request)
    {
        return $this->channelService->create($request->input('user_ids') ?? [], $request->validated());
    }
}
