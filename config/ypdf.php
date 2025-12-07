<?php

return [
    'driver' => env('YPDF_DRIVER', 'chrome'),

    'temp_directory' => env('YPDF_TEMP', storage_path('app/ypdf')),

    'chrome' => [
        'binary_path' => env('YPDF_CHROME_BINARY', null),
        'node_binary' => env('YPDF_NODE_BINARY', null),
        'enable_images' => env('YPDF_ENABLE_IMAGES', true),
        'no_sandbox' => env('YPDF_NO_SANDBOX', false),
        'timeout' => env('YPDF_TIMEOUT', 60),
        'viewport' => [
            'width' => env('YPDF_VIEWPORT_WIDTH', 1280),
            'height' => env('YPDF_VIEWPORT_HEIGHT', 720),
            'deviceScaleFactor' => env('YPDF_DEVICE_SCALE', 1)
        ],
        'pdf' => [
            'format' => env('YPDF_FORMAT', 'A4'),
            'marginTop' => env('YPDF_MARGIN_TOP', 10),
            'marginRight' => env('YPDF_MARGIN_RIGHT', 10),
            'marginBottom' => env('YPDF_MARGIN_BOTTOM', 10),
            'marginLeft' => env('YPDF_MARGIN_LEFT', 10),
            'printBackground' => env('YPDF_PRINT_BACKGROUND', true)
        ],
    ],
];
