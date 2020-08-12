<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Chat\Channel\StoreChannelRequest;
use App\Http\Requests\Chat\Channel\UpdateChannelRequest;
use App\Http\Resources\Channel\ChannelCollection;
use App\Http\Resources\Channel\ChannelResource;
use App\Models\Channel;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class ChannelsController extends Controller
{
    /**
     * Creating chat instance
     *
     * @param StoreChannelRequest $request
     * @return ChannelResource
     */
    public function store(StoreChannelRequest $request)
    {
        return new ChannelResource(
            Chat::channels()->create(
                $request->input('usernames') ?? [],
                $request->validated()
            ));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ChannelResource
     * @throws AuthorizationException
     */
    public function show(int $id)
    {
        $chat = Channel::find($id);
        $this->authorize('access', $chat);

        return new ChannelResource($chat);
    }

    /**
     * Display a listing of the resource.
     *
     * @return ChannelCollection
     */
    public function index()
    {
        return new ChannelCollection(
            auth()->user()->channels
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateChannelRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateChannelRequest $request, int $id)
    {
        Channel::find($id)->update($request->validated());

        return response()->noContent();
    }
}
