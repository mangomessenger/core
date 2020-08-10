<?php

namespace App\Models;

use App\Contracts\Chat;
use App\Models\BaseChat;

class DirectChat extends BaseChat implements Chat
{
    /**
     * Remove members from chat. (Unable in direct chat)
     *
     * @param $members
     * @return void
     */
    public function removeMembers($members) { }
}
