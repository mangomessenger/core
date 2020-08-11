<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Message\SendMessageRequest;
use App\Http\Resources\Message\MessageResource;
use App\Services\Message\MessageService;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Instance of message service.
     *
     * @var MessageService $messageService
     */
    private MessageService $messageService;

    /**
     * MessagesController constructor.
     *
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
