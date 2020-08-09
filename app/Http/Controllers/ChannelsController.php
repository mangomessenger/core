<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\ChatRequest;
use App\Models\Channel;
use App\Models\DirectChat;
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
     * @param ChatRequest $request
     * @return Channel|DirectChat
     */
    public function create(ChatRequest $request)
    {
        return $this->directChatService->create($request->input('user_ids'), $request->validated());
    }
}
