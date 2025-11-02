<?php

return [

    'loading' => [
        'enabled' => env('APP_LOADING', false),
        'color' => '#3490dc',
        'icon' => 'fa-regular fa-spinner fa-pulse fa-3x fa-fw text-primary',
        'opacity' => 0.8,
        'zindex' => 9999,
        'fadeout' => 500,
        'delay' => 0,
    ],
    'date' => [
        'format' => 'Y-m-d',
        'display_format' => 'F j, Y',
    ],
    'datetime' => [
        'format' => 'Y-m-d H:i:s',
        'display_format' => 'F j, Y g:i A',
    ],
    'languages' => [
        'en' => [
            'code' => 'en',
            'variant' => 'en-US',
            'name' => 'English',
            'flag' => 'assets/icons/flags/en.svg',
        ],
        'km' => [
            'code' => 'km',
            'variant' => 'km-KH',
            'name' => 'ខ្មែរ',
            'flag' => 'assets/icons/flags/km.svg',
        ],
    ],
    'language_variants' => [
        'km' => 'km-KH',
        'en' => 'en-US'
    ],
    'available_locales' => ['km', 'en'],
];
