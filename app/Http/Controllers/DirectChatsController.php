<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Chat\DirectChat\StoreDirectChatRequest;
use App\Http\Resources\DirectChat\DirectChatResource;

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
            Chat::directChats()->create($request->validated()['username'])
        );
    }
}
