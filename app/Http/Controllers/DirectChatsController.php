<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Chat\DirectChat\StoreDirectChatRequest;
use App\Http\Resources\DirectChat\DirectChatCollection;
use App\Http\Resources\DirectChat\DirectChatResource;
use App\Models\DirectChat;
use Illuminate\Auth\Access\AuthorizationException;

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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return DirectChatResource
     * @throws AuthorizationException
     */
    public function show(int $id)
    {
        $chat = DirectChat::find($id);
        $this->authorize('access', $chat);

        return new DirectChatResource($chat);
    }

    /**
     * Display a listing of the resource.
     *
     * @return DirectChatCollection
     */
    public function index()
    {
        $user = auth()->user();

        return new DirectChatCollection(
            auth()->user()->directChats
        );
    }
}
