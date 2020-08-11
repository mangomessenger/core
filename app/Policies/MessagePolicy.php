<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given message can be accessed by the user.
     *
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function access(User $user, Message $message)
    {
        return $message->chat->members->contains($user);
    }

    /**
     * Determine if the given message can be updated by the user.
     *
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function update(User $user, Message $message)
    {
        return $message->sender->is($user);
    }

    /**
     * Determine if the given message can be deleted by the user.
     *
     * @param User $user
     * @param Message $message
     * @return bool
     */
    public function delete(User $user, Message $message)
    {
        return $message->sender->is($user)
            && $message->created_at > Carbon::now()->subDays(1); // After 1 day message could not be deleted
    }
}
