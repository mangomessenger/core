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
        return [
            'direct' => new DirectChatCollection(
                Chat::directChats()
                    ->findByUserId(auth()->user()->id)
            ),
            'channels' => new ChannelCollection(
                Chat::channels()
                    ->findByUserId(auth()->user()->id)
            ),
            'groups' => new GroupCollection(
                Chat::groups()
                    ->findByUserId(auth()->user()->id)
            ),
        ];
    }
}
