<?php

return [
    'autoload' => false,
    'hooks' => [
        'response_send' => [
            'loginbgindex',
        ],
        'index_login_init' => [
            'loginbgindex',
        ],
    ],
    'route' => [],
    'priority' => [],
    'domain' => '',
];
