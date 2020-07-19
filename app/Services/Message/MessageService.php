<?php

namespace App\Services\Message;

use App\Exceptions\Message\ChatTypeInvalidException;
use App\Exceptions\Message\DestinationInvalidException;
use App\Message;
use App\Services\Auth\UserService;
use App\Services\Chat\ChatService;
use App\Services\ModelService;
use Illuminate\Database\Eloquent\Collection;
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
        // Getting peer array
        $peer = $data['peer'];

        switch ($peer['chat_type']) {
            case 'TYPE_USER':
                if (!$this->userService
                    ->exists($peer['destination_id'])) {
                    throw new DestinationInvalidException();
                }

                // Find chat that connects both users
                $chat = $this->chatService->firstByUsers($peer['destination_id'], auth()->user()->id);

                // Getting message out of transaction
                $message = DB::transaction(function () use ($data, $peer, $chat) {
                    // If chat does not exist - create it
                    if (is_null($chat)) {
                        $chat = $this->chatService->create([
                            'user1_id' => $peer['destination_id'],
                            'user2_id' => auth()->user()->id,
                            'type' => $peer['destination_id'] == auth()->user()->id ? 'TYPE_SELF' : 'TYPE_USER',
                        ]);
                    }

                    // Creating message instance
                    return $this->create([
                        'chat_id' => $chat->id,
                        'user_id' => auth()->user()->id,
                        'message' => $data['message'],
                    ]);
                }, 5);

                // Update update_at column
                $chat->touch();

                break;
            default:
                throw new ChatTypeInvalidException();
        }

        return $message;
    }

    /**
     * @param int $chat_id
     * @param int|null $messageId
     * @return mixed
     */
    public function getMessages(int $chat_id, int $messageId = null): Collection
    {
        if (is_null($messageId)){
            return $this->model
                ->where('chat_id', $chat_id)
                ->get();
        }
        else {
            return $this->model
                ->where('chat_id', $chat_id)
                ->where('id', '>', $messageId)
                ->get();
        }
    }
}
