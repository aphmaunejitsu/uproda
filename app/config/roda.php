<?php

return [
    'name'       => env('RODA_NAME', 'ネ実うpろだ'),
    'service' => [
        'cache' => env('RODA_SERVICE_CACHE', 60),
    ],
    'url'        => [
        'image' => [
            'thumbnail' => env('RODA_THUMBNAIL_DIR', 'thumbnail')
        ]
    ],
    'storage' => env('RODA_IMAGE_STORAGE', 'image'),
    'pagination' => [
        'perPage' => env('RODA_PAGINATION_PERPAGE', 100),
    ],
    'delkey' => env('RODA_DELKEY', 'password'),
    'upload' => [
        'max' => env('RODA_UPLOAD_MAXSIZE', 20971520),
    ],
];
