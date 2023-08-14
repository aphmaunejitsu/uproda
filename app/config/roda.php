<?php

return [
    'name' => env('RODA_NAME', 'ネ実うpろだ'),
    'service' => [
        'cache' => env('RODA_SERVICE_CACHE', 60),
    ],
    'url'        => [
        'image' => [
            'thumbnail' => env('RODA_THUMBNAIL_DIR', 'thumbnail'),
            'dir'       => env('RODA_IMAGE_DIR', 'up'),
        ],
        'tor' => env('RODA_TOR_URL', 'https://check.torproject.org/exit-addresses'),
    ],
    'storage' => [
        'image' => env('RODA_IMAGE_STORAGE', 'image'),
        'chunk' => env('RODA_CHUNK_STORAGE', 'chunk'),
        'tmp'   => env('RODA_TMP_STORAGE', 'tmp'),
    ],
    'pagination' => [
        'perPage' => env('RODA_PAGINATION_PERPAGE', 100),
    ],
    'delkey' => env('RODA_DELKEY', 'password'),
    'upload' => [
        'max' => env('RODA_UPLOAD_MAXSIZE', 20480),
    ],
    'thumbnail' => [
        'width'  => env('RODA_THUMBNAIL_WIDTH', 400),
        'height' => env('RODA_THUMBNAIL_HEIGHT', 400),
    ],
    'fake' => [
        'location' => [
            'latitude'  => env('RODA_LATITUDE', 35.8385569),
            'longitude' => env('RODA_LONGITUDE', 0),
            'altitude'  => env('RODA_ALTITUDE', 3),
        ]
    ],
    'google' => [
        'recaptcha' => [
            'verify' => env('RODA_GOOGLE_RECAPTCHA_VERIFY'),
            'secret' => env('RODA_GOOGLE_RECAPTCHA_SECRET'),
            'cache'  => env('RODA_GOOGLE_RECAPTCHA_CACHE'),
            'score'  => env('RODA_GOOGLE_RECAPTCHA_SCORE', 0),
        ],
    ],
];
