<?php

namespace App\Services\Auth;

use App\AuthRequest;
use App\Services\ModelService;

class AuthRequestService extends ModelService
{
    protected AuthRequest $model;

    /**
     * AuthRequestService constructor.
     *
     * @param AuthRequest $error
     */
    public function __construct(AuthRequest $error)
    {
        $this->model = $error;
    }

}
