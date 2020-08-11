<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\ChatType;
use App\Services\Message\MessageService;

class MessagesController extends Controller
{
    /**
     * Instance of message service.
     *
     * @var MessageService $messageService
     */
    private MessageService $messageService;

    /**
     * MessagesController constructor.
     *
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Sending a message in chat
     *
     * @param SendMessageRequest $request
     * @param ChatType $chatType
     * @param int $chatId
     * @return MessageResource
     */
    public function sendMessage(SendMessageRequest $request, ChatType $chatType, int $chatId)
    {
        $chat = Chat::chats()->findChat($chatType, $chatId);

        return new MessageResource(
            Chat::message(
                $request->input('message'))
                ->from(auth()->user())
                ->to($chat)
                ->send()
        );
    }
}
