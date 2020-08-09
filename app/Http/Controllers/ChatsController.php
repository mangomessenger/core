<?php

namespace App\Http\Controllers;

use App\ChatType;
use App\Http\Requests\Chat\ChatRequest;
use App\Http\Resources\ChatCollection;
use App\Services\Chat\ChatService;
use App\Services\Chat\DirectChatService;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    /**
     * Instance of chat service.
     *
     * @var DirectChatService $directChatService
     */
    private DirectChatService $directChatService;

    /**
     * MessagesController constructor.
     *
     * @param DirectChatService $directChatService
     */
    public function __construct(DirectChatService $directChatService)
    {
        $this->directChatService = $directChatService;
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
     * @return void
     */
    public function create(ChatRequest $request, ChatType $chatType)
    {
        if ($chatType->isDirect()) {
            return $this->directChatService->create($request->validated()['user_ids']);
        }
    }
}
