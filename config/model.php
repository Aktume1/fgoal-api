<?php

return [
    'user' => [
        'status' => [
            'inactive' => 0,
            'working' => 1,
            'banned' => 2,
        ],
        'gender' => [
            'male' => 0,
            'female' => 1,
        ],
    ],
    'group' => [
        'role' => [
            'manager' => 1,
            'member' => 0,
        ],
    ],
    'objective' => [
        'process' => [
            'off' => 0,
            'inprocess' => 1,
            'done' => 2,
        ],
    ],
    'workspace' => [
        'is_manager' => 1,
    ]
];
