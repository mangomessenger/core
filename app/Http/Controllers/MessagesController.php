<?php

namespace App\Http\Controllers;

use App\Exceptions\Message\ChatTypeInvalidException;
use App\Exceptions\Message\DestinationInvalidException;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Resources\MessageResource;
use App\Services\Message\MessageService;
use Illuminate\Http\Response;

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
     * Display a listing of the resource.
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

}
