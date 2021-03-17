<?php

return [
    'name'    => env('RODA_NAME', 'ネ実うpろだ'),
    'service' => [
        'cache' => env('RODA_SERVICE_CACHE', 60),
    ],
    'url'     => [
        'image' => [
            'base'      => env('RODA_URL_IMAGE_BASE', 'http://localhost'),
            'thumbnail' => env('RODA_THUMBNAIL_DIR', 'thumbnail')
        ]
    ]
];
