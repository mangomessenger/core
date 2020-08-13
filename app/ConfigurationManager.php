<?php

namespace App;

class ConfigurationManager
{
    const USER_LIMITS = [
        'username' => [
            'min' => 3,
            'max' => 32,
        ],
        'bio' => [
            'max' => 70,
        ],
        'name' => [
            'min' => 1,
            'max' => 64,
        ],
        'daily_chats' => [
            'max' => 10, // Daily maximum 10 chats
        ]
    ];

    const GROUP_LIMITS = [
        'title' => [
            'min' => 1,
            'max' => 64,
        ],
        'description' => [
            'min' => 1,
            'max' => 256,
        ],
        'admins' => [
            'max' => 10, // Maximum 10 admins in group
        ],
    ];

    const CHANNEL_LIMITS = [
        'title' => [
            'min' => 1,
            'max' => 64,
        ],
        'description' => [
            'min' => 1,
            'max' => 256,
        ],
        'tag' => [
            'min' => 3,
            'max' => 32,
        ],
        'admins' => [
            'max' => 10, // Maximum 10 admins in channel
        ],
    ];

    const MESSAGE_LIMITS = [
        'message' => [
            'min' => 1,
            'max' => 1024
        ],
    ];

    const USER_RULES = [
        'name' => [
            'string',
            'min:' . self::USER_LIMITS['name']['min'],
            'max:' . self::USER_LIMITS['name']['max'],
        ],
        'bio' => [
            'max:' . self::USER_LIMITS['bio']['max'],
        ],
        'username' => [
            'nullable',
            'string',
            'regex:/(^[A-Za-z0-9]+$)+/',
            'min:' . self::USER_LIMITS['username']['min'],
            'max:' . self::USER_LIMITS['username']['max'],
        ],
        'phone_number' => [
            'required',
            'phone:country_code',
        ],
        'country_code' => [
            'required_with:phone'
        ],
        'photo' => 'photo'
    ];

    const AUTH_RULES = [
        'fingerprint' => 'required|string|min:10|max:255',
        'refresh_token' => 'required|string',
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
    ];

    const CHANNEL_RULES = [
        'title' => [
            'string',
            'min:' . self::CHANNEL_LIMITS['title']['min'],
            'max:' . self::CHANNEL_LIMITS['title']['max'],
        ],
        'description' => 'max:255',
        'tag' => self::USER_RULES['username'],
        'photo' => 'photo'
    ];

    const GROUP_RULES = [
        'title' => [
            'string',
            'min:' . self::CHANNEL_LIMITS['title']['min'],
            'max:' . self::CHANNEL_LIMITS['title']['max'],
        ],
        'description' => 'max:255',
        'photo' => 'photo'
    ];

    const MESSAGE_RULES = [
        'message' => [
            'required',
            'string',
            'min:' . self::MESSAGE_LIMITS['message']['min'],
            'max:' . self::MESSAGE_LIMITS['message']['max'],
        ]
    ];

    public static function limits()
    {
        return [
            'user' => self::USER_LIMITS,
            'group' => self::GROUP_LIMITS,
            'channel' => self::CHANNEL_LIMITS,
        ];
    }
}
