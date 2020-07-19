<?php

namespace App\Http\Controllers;

use App\Exceptions\Message\ChatTypeInvalidException;
use App\Exceptions\Message\DestinationInvalidException;
use App\Http\Requests\Message\GetMessagesRequest;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
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
     * @param SendMessageRequest $sendMessageRequest
     * @return MessageResource
     *
     * @throws ChatTypeInvalidException
     * @throws DestinationInvalidException
     */
    public function sendMessage(SendMessageRequest $sendMessageRequest)
    {
        $message = $this->messageService->sendMessage($sendMessageRequest->validated());

        return new MessageResource($message);
    }

    /**
     * Getting all messages by chat
     *
     * @param GetMessagesRequest $request
     * @return MessageCollection
     */
    public function getMessages(GetMessagesRequest $request)
    {
        $validRequest = $request->validated();

        $messages = $this->messageService->getMessages($validRequest['chat_id'], $validRequest['message_id'] ?? null);

        return new MessageCollection($messages);
    }

}
