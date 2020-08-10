<?php

namespace App\Models;

use App\Models\BaseChat;

class DirectChat extends BaseChat
{
    /**
     * Remove members from chat. (Unable in direct chat)
     *
     * @param $members
     * @return void
     */
    public function removeMembers($members) { }
}
