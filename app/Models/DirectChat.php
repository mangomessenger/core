<?php

namespace App\Models;

use App\Facades\Snowflake;
use Illuminate\Database\Eloquent\Model;

class DirectChat extends Chat
{
    /**
     * Remove members from chat. (Unable in direct chat)
     *
     * @param $members
     * @return void
     */
    public function removeMembers($members) { }
}
