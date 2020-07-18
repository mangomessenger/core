<?php

namespace App\Services\Auth;

use App\Services\ModelService;
use App\Session;

class SessionService extends ModelService
{
    protected Session $model;

    /**
     * AuthRequestService constructor.
     *
     * @param Session $user
     */
    public function __construct(Session $user)
    {
        $this->model = $user;
    }

}
