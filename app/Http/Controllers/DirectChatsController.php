<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
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
            Chat::directChats()->create($request->validated()['user_id'])
        );
    }
}
