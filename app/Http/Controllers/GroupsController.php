<?php

namespace App\Http\Controllers;

use App\Facades\Chat;
use App\Http\Requests\Chat\Group\StoreGroupRequest;
use App\Http\Requests\Chat\Group\UpdateGroupRequest;
use App\Http\Resources\Group\GroupCollection;
use App\Http\Resources\Group\GroupResource;
use App\Models\Group;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;

class GroupsController extends Controller
{
    /**
     * Creating chat instance
     *
     * @param StoreGroupRequest $request
     * @return GroupResource
     */
    public function store(StoreGroupRequest $request)
    {
        return new GroupResource(
            Chat::groups()->create($request->input('usernames') ?? [], $request->validated())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return GroupResource
     * @throws AuthorizationException
     */
    public function show(int $id)
    {
        $chat = Group::find($id);
        $this->authorize('access', $chat);

        return new GroupResource($chat);
    }

    /**
     * Display a listing of the resource.
     *
     * @return GroupCollection
     */
    public function index()
    {
        $user = auth()->user();

        return new GroupCollection(
            auth()->user()->groups
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateGroupRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateGroupRequest $request, int $id)
    {
        Group::find($id)->update($request->validated());

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
