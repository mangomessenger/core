<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application User Rules
    |--------------------------------------------------------------------------
    */

    'users' => [
        'name' => [
            'string',
            'min:' . config('limits.users.username.min'),
            'max:' . config('limits.users.username.max'),
        ],
        'bio' => [
            'max:' . config('limits.users.bio.max'),
        ],
        'username' => [
            'nullable',
            'string',
            'regex:/(^[A-Za-z0-9]+$)+/',
            'min:' . config('limits.users.username.min'),
            'max:' . config('limits.users.username.max'),
        ],
        'phone_number' => [
            'required',
            'phone:country_code',
        ],
        'country_code' => [
            'required_with:phone'
        ],
        'photo' => 'photo'
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Auth Rules
    |--------------------------------------------------------------------------
    */

    'auth' => [
        'fingerprint' => [
            'required',
            'string',
            'min:10',
            'max:255',
        ],
        'refresh_token' => [
            'required',
            'string'
        ],
        'phone_code_hash' => [
            'required',
            'string',
            'max:255',
        ],
        'phone_code' => [
            'required',
            'digits:5',
        ],
        'terms_of_service_accepted' => [
            'required',
            'accepted',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Channel Rules
    |--------------------------------------------------------------------------
    */

    'channels' => [
        'title' => [
            'string',
            'min:' . config('limits.channels.title.min'),
            'min:' . config('limits.channels.title.max'),
        ],
        'description' => 'max:255',
        'tag' => [
            'nullable',
            'string',
            'regex:/(^[A-Za-z0-9]+$)+/',
            'min:' . config('limits.channels.tag.min'),
            'max:' . config('limits.channels.tag.max'),
        ],
        'photo' => 'photo'
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Group Rules
    |--------------------------------------------------------------------------
    */

    'groups' => [
        'title' => [
            'string',
            'min:' . config('limits.groups.title.min'),
            'min:' . config('limits.groups.title.max'),
        ],
        'description' => 'max:255',
        'photo' => 'photo'
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Message Rules
    |--------------------------------------------------------------------------
    */

    'messages' => [
        'message' => [
            'required',
            'string',
            'min:' . config('limits.messages.message.min'),
            'max:' . config('limits.messages.message.max'),
        ]
    ],

];
