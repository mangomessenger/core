<?php

namespace App\Policies;

use App\Chat;
use App\Models\BaseChat;
use App\Models\Channel;
use App\Services\Chat\ChatService;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Chat service instance.
     *
     * @var ChatService
     */
    private ChatService $chatService;

    /**
     * Create a new policy instance.
     *
     * @param ChatService $chatService
     */
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Determine if the given chat can be accessed by the user.
     *
     * @param User $user
     * @param BaseChat $chat
     * @return bool
     */
    public function access(User $user, BaseChat $chat)
    {
        return $chat->members->contains($user);
    }
}
