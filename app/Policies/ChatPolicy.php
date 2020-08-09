<?php

namespace App\Policies;

use App\Chat;
use App\Services\Chat\ChatService;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    use HandlesAuthorization;

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
     * Determine if the given post can be updated by the user.
     *
     * @param User $user
     * @param Chat $chat
     * @return bool
     */
    public function view(User $user, Chat $chat)
    {
        return $this->chatService->existsByUser($chat->id, $user->id);
    }
}
