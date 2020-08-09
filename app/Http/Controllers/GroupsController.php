<?php

namespace App\Http\Controllers;

use App\Http\Requests\Chat\StoreChannelRequest;
use App\Http\Requests\Chat\StoreGroupRequest;
use App\Models\Channel;
use App\Models\DirectChat;
use App\Models\Group;
use App\Services\Chat\ChannelService;
use App\Services\Chat\GroupService;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Instance of channel chat service.
     *
     * @var GroupService $groupService
     */
    private GroupService $groupService;

    /**
     * MessagesController constructor.
     *
     * @param GroupService $groupService
     */
    public function __construct(
        GroupService $groupService
    )
    {
        $this->groupService = $groupService;
    }

    /**
     * Creating chat instance
     *
     * @param StoreGroupRequest $request
     * @return Group
     */
    public function store(StoreGroupRequest $request)
    {
        return $this->groupService->create($request->input('user_ids') ?? [], $request->validated());
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
