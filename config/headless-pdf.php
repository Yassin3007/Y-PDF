<?php

return [
    'driver' => env('HEADLESS_PDF_DRIVER', 'chrome'),

    'temp_directory' => env('HEADLESS_PDF_TEMP', storage_path('app/headless-pdf')),

    'chrome' => [
        'binary_path' => env('HEADLESS_PDF_CHROME_BINARY', null),
        'node_binary' => env('HEADLESS_PDF_NODE_BINARY', null),
        'enable_images' => env('HEADLESS_PDF_ENABLE_IMAGES', true),
        'no_sandbox' => env('HEADLESS_PDF_NO_SANDBOX', false),
        'timeout' => env('HEADLESS_PDF_TIMEOUT', 60),
        'viewport' => [
            'width' => env('HEADLESS_PDF_VIEWPORT_WIDTH', 1280),
            'height' => env('HEADLESS_PDF_VIEWPORT_HEIGHT', 720),
            'deviceScaleFactor' => env('HEADLESS_PDF_DEVICE_SCALE', 1)
        ],
        'pdf' => [
            'format' => env('HEADLESS_PDF_FORMAT', 'A4'),
            'marginTop' => env('HEADLESS_PDF_MARGIN_TOP', 10),
            'marginRight' => env('HEADLESS_PDF_MARGIN_RIGHT', 10),
            'marginBottom' => env('HEADLESS_PDF_MARGIN_BOTTOM', 10),
            'marginLeft' => env('HEADLESS_PDF_MARGIN_LEFT', 10),
            'printBackground' => env('HEADLESS_PDF_PRINT_BACKGROUND', true)
        ],
    ],
];
