<?php

namespace App\Http\Controllers;

use App\Facades\ChatFacade;
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
                ChatFacade::directChats()
                    ->findByUserId(auth()->user()->id)
            ),
            'channels' => new ChannelCollection(
                ChatFacade::channels()
                    ->findByUserId(auth()->user()->id)
            ),
            'groups' => new GroupCollection(
                ChatFacade::groups()
                    ->findByUserId(auth()->user()->id)
            ),
        ];
    }
}
