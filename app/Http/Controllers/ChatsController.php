<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Resources\ChannelCollection;
use App\Http\Resources\DirectChatCollection;
use App\Http\Resources\GroupCollection;

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
