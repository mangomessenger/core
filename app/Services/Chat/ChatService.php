<?php

namespace App\Services\Chat;

use App\Models\Channel;
use App\Models\ChatType;
use App\Models\DirectChat;
use App\Models\Group;

class ChatService
{
    public function findChat(ChatType $chatType, int $chatId)
    {
        switch ($chatType->name) {
            case 'direct':
                return DirectChat::find($chatId);
            case 'channel':
                return Channel::find($chatId);
            case 'group':
                return Group::find($chatId);
        }
    }
}
