<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Chat\DirectChat\StoreDirectChatRequest;
use App\Http\Resources\DirectChat\DirectChatCollection;

class DirectChatsController extends Controller
{
    /**
     * Creating chat instance
     *
     * @param StoreDirectChatRequest $request
     * @return DirectChatCollection
     */
    public function store(StoreDirectChatRequest $request)
    {
        return new DirectChatCollection(
            Chat::directChats()->create($request->validated()['username'])
        );
    }
}
