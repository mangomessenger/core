<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Message\DestroyMessageRequest;
use App\Http\Requests\Message\IndexMessagesRequest;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Requests\Message\ShowMessageRequest;
use App\Http\Requests\Message\UpdateMessageRequest;
use App\Http\Resources\Message\MessageCollection;
use App\Http\Resources\Message\MessageResource;
use Illuminate\Http\Response;

class MessagesController extends Controller
{
    /**
     * Sending a message in chat
     *
     * @param SendMessageRequest $request
     * @return MessageResource
     */
    public function store(SendMessageRequest $request)
    {
        $chat = Chat::chats()->findChat(
            $request->input('chat_type'),
            $request->input('chat_id')
        );

        return new MessageResource(
            Chat::message(
                $request->input('message'))
                ->from(auth()->user())
                ->to($chat)
                ->send()
        );
    }

    /**
     * Update the message
     *
     * @param UpdateMessageRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateMessageRequest $request, int $id)
    {
        Chat::messages()->update($id, $request->validated());

        return response()->noContent();
    }

    /**
     * Removes messages from chat.
     *
     * @param DestroyMessageRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(DestroyMessageRequest $request, int $id)
    {
        Chat::messages()->delete($id);

        return response()->noContent();
    }

    /**
     * Displays message
     *
     * @param ShowMessageRequest $request
     * @param int $id
     * @return MessageResource
     */
    public function show(ShowMessageRequest $request, $id)
    {
        return new MessageResource(
            Chat::messages()->find($id)
        );
    }

    /**
     * Display a list of messages from chat
     *
     * @param IndexMessagesRequest $request
     * @return MessageCollection
     */
    public function index(IndexMessagesRequest $request)
    {
        $chat = Chat::chats()->findChat(
            $request->input('chat_type'),
            $request->input('chat_id')
        );

        return new MessageCollection($chat->messages);
    }

}
