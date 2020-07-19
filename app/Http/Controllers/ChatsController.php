<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatCollection;
use App\Services\Chat\ChatService;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    /**
     * Instance of chat service.
     *
     * @var ChatService $chatService
     */
    private ChatService $chatService;

    /**
     * MessagesController constructor.
     *
     * @param ChatService $chatService
     */
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
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
}
