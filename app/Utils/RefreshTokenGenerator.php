<?php

namespace App\Utils;

use Exception;

class RefreshTokenGenerator
{
    /**
     * Generates refresh token.
     *
     * @return string
     * @throws Exception
     */
    public static function generate(): string
    {
        return bin2hex(random_bytes(64));
    }
}
