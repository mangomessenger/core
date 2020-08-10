<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Chat\StoreDirectChatRequest;
use App\Models\DirectChat;

class DirectChatsController extends Controller
{
    /**
     * Creating chat instance
     *
     * @param StoreDirectChatRequest $request
     * @return DirectChat
     */
    public function store(StoreDirectChatRequest $request)
    {
        return Chat::directChats()->create($request->validated()['user_id']);
    }
}
