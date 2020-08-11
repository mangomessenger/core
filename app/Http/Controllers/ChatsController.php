<?php

namespace App\Http\Controllers;

use App\Http\Resources\Channel\ChannelCollection;
use App\Http\Resources\DirectChat\DirectChatCollection;
use App\Http\Resources\Group\GroupCollection;

class ChatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string[]
     */
    public function index()
    {
        $user = auth()->user();

        return [
            'direct' => new DirectChatCollection(
                $user->directChats
            ),
            'channels' => new ChannelCollection(
                $user->channels
            ),
            'groups' => new GroupCollection(
                $user->groups
            ),
        ];
    }
}
