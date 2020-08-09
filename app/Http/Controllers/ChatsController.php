<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\ChatType;
use App\Http\Requests\Chat\ChatRequest;
use App\Http\Resources\ChatCollection;
use App\Models\DirectChat;
use App\Services\Chat\ChannelService;
use App\Services\Chat\ChatService;
use App\Services\Chat\DirectChatService;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    /**
     * Instance of direct chat service.
     *
     * @var DirectChatService $directChatService
     */
    private DirectChatService $directChatService;

    /**
     * Instance of channel chat service.
     *
     * @var ChannelService $channelService
     */
    private ChannelService $channelService;

    /**
     * MessagesController constructor.
     *
     * @param DirectChatService $directChatService
     * @param ChannelService $channelService
     */
    public function __construct(
        DirectChatService $directChatService,
        ChannelService $channelService
    )
    {
        $this->directChatService = $directChatService;
        $this->channelService = $channelService;
    }

    /**
     * Getting all user's chats
     *
     * @param Request $request
     * @return ChatCollection
     */
    public function getChats(Request $request)
    {
        $chats = $this->chatService->getChats(auth()->user()->id);

        return new ChatCollection($chats);
    }

    /**
     * Creating chat instance
     *
     * @param ChatRequest $request
     * @param ChatType $chatType
     * @return Channel|DirectChat
     */
    public function create(ChatRequest $request, ChatType $chatType)
    {
        if ($chatType->isDirect()) {
            return $this->directChatService->create($request->input('user_ids'), $request->validated());
        } else if ($chatType->isChannel()) {
            return $this->channelService->create($request->input('user_ids'), $request->validated());
        }
    }
}
