<?php

namespace App\Contracts;

interface Chat
{
    public function members();
    public function messages();
    public function users();
    public function addMembers($members);
    public function removeMembers($members);
    public function hasMember(int $userId);
}
