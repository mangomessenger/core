<?php

namespace App\Services\Message;

use App\Chat;
use App\ChatMember;
use App\Exceptions\Message\ChatTypeInvalidException;
use App\Exceptions\Message\DestinationInvalidException;
use App\Message;
use App\Services\Auth\UserService;
use App\Services\ModelService;
use Illuminate\Support\Facades\DB;

class MessageService extends ModelService
{
    /**
     * Message instance
     *
     * @var Message
     */
    protected Message $model;

    /**
     * UserService instance
     *
     * @var UserService
     */
    private UserService $userService;

    /**
     * ChatService instance
     *
     * @var ChatService
     */
    private ChatService $chatService;

    /**
     * MessageService constructor.
     *
     * @param Message $message
     * @param UserService $userService
     * @param ChatService $chatService
     */
    public function __construct(Message $message,
                                UserService $userService,
                                ChatService $chatService)
    {
        $this->model = $message;
        $this->userService = $userService;
        $this->chatService = $chatService;
    }


    /**
     * Sends message to destination.
     *
     * @param array $data
     * @return Message
     *
     * @throws ChatTypeInvalidException
     * @throws DestinationInvalidException
     */
    public function sendMessage(array $data): Message
    {
        $peer = $data['peer'];
        $destUserId = $peer['destination_id'];

        switch ($peer['chat_type']) {
            case 'TYPE_USER':
                if (!$this->userService
                    ->exists($destUserId)) {
                    throw new DestinationInvalidException();
                }

                $chat = $this->chatService->findByUsers($destUserId, auth()->user()->id);

                if (is_null($chat)){
                    $chat = $this->chatService->create([
                        'user1_id' => $destUserId,
                        'user2_id' => auth()->user()->id,
                        'type' => $destUserId === auth()->user()->id ? 'TYPE_SELF' : 'TYPE_USER',
                    ]);
                }

                $message = $this->create([
                    'chat_id' => $chat->id,
                    'user_id' => auth()->user()->id,
                    'message' => $data['message'],
                ]);

                break;
            default:
                throw new ChatTypeInvalidException();
        }


        return $message;
    }
}
