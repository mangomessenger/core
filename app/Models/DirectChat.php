<?php

namespace App\Models;

use App\Abstracts\AbstractChat;

class DirectChat extends AbstractChat
{
    /**
     * Remove members from chat. (Unable in direct chat)
     *
     * @param $members
     * @return void
     */
    public function removeMembers($members) { }
}
