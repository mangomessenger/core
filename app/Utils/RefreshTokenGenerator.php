<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Str;

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
        return Str::uuid();
    }
}
