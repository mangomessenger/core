<?php

namespace App\Services\Message;

use App\Exceptions\Chat\ChatInvalidException;
use App\Models\BaseChat;
use App\Models\Message;
use App\Services\User\UserService;
use App\Services\ModelService;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MessageService extends ModelService
{
    /**
     * Message type
     *
     * @var string $type
     */
    protected string $type = 'text';

    /**
     * String message
     *
     * @var string $message
     */
    protected string $message;

    /**
     * Message sender
     *
     * @var Authenticatable $sender
     */
    protected ?Authenticatable $sender;

    /**
     * @var BaseChat $chat
     */
    protected ?BaseChat $chat;

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
     * MessageService constructor.
     *
     * @param Message $message
     * @param UserService $userService
     */
    public function __construct(Message $message,
                                UserService $userService)
    {
        $this->model = $message;
        $this->userService = $userService;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Sets the participant that's sending the message.
     *
     * @param Authenticatable $sender
     *
     * @return $this
     */
    public function from(?Authenticatable $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Sets the participant to receive the message.
     *
     * @param BaseChat $chat
     *
     * @return $this
     */
    public function to(?BaseChat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * Sends the message.
     *
     * @return Message
     * @throws Exception
     */
    public function send(): Message
    {
        if (is_null($this->sender) ||
            strlen($this->message) == 0) {
            throw new Exception();
        }

        if (!$this->chat) {
            throw new ChatInvalidException();
        }

        if (!$this->chat->members->contains($this->sender)) {
            throw new Exception('User is not a member of chat.');
        }

        return DB::transaction(function ()  {
            $this->chat->touch();

            return $this->chat->messages()->create([
                'from_id' => $this->sender->id,
                'chat_id' => $this->chat->id,
                'message' => $this->message
            ]);
        });
    }
}
