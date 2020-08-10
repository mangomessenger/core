<?php

namespace App\Services\Auth;

use App\Services\ModelService;
use App\Models\Session;
use App\Utils\RefreshTokenGenerator;
use Exception;

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
     * @throws Exception
     */
    public function create(array $input)
    {
        if (!isset($input['refresh_token'])) {
            $input['refresh_token'] = RefreshTokenGenerator::generate();
        }

        return $this->model->create($input);
    }
}
