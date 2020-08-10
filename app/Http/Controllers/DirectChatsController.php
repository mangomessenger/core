<?php

namespace App\Http\Controllers;

use App\Facades\ChatFacade;
use App\Http\Requests\Chat\StoreDirectChatRequest;
use App\Http\Resources\DirectChatResource;
use App\Models\DirectChat;

class DirectChatsController extends Controller
{
    /**
     * Creating chat instance
     *
     * @param StoreDirectChatRequest $request
     * @return DirectChatResource
     */
    public function store(StoreDirectChatRequest $request)
    {
        return new DirectChatResource(
            ChatFacade::directChats()->create($request->validated()['username'])
        );
    }
}
