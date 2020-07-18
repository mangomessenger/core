<?php

namespace App\Services\Auth;

use App\Services\ModelService;
use App\Session;
use App\Utils\RefreshTokenGenerator;

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

    /**
     * @param array $input
     * @return mixed
     */
    public function create(array $input)
    {
        if (!isset($input['refresh_token'])) {
            $input['refresh_token'] = RefreshTokenGenerator::generate();
        }

        return $this->model->create($input);
    }
}
