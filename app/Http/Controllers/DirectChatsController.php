<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\ChatRequest;
use App\Models\Channel;
use App\Models\DirectChat;
use App\Services\Chat\ChannelService;
use App\Services\Chat\DirectChatService;

class DirectChatsController extends Controller
{
    /**
     * Instance of direct chat service.
     *
     * @var DirectChatService $directChatService
     */
    private DirectChatService $directChatService;

    /**
     * MessagesController constructor.
     *
     * @param DirectChatService $directChatService
     */
    public function __construct(
        DirectChatService $directChatService
    )
    {
        $this->directChatService = $directChatService;
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
