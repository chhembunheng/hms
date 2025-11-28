<?php

return [
    'layout_version' => rand(1000, 9999),

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
    'sections' => [
        'index' => ['sliders', 'services', 'integrations', 'talk', 'achievements', 'contacts'],
        'services' => ['services', 'talk'],
        'products' => ['products', 'talk'],
        'pricing' => ['pricing_plans'],
        'careers' => ['careers', 'talk'],
        'blogs' => ['blogs', 'talk'],
        'integrations' => ['integrations', 'talk'],
        'teams' => ['teams', 'talk'],
        'faqs' => ['faqs', 'talk']
    ],
    'positions' => [
        1 => [
            'en' => 'Assistant Director',
            'km' => 'ជំនួយការនាយក',
        ],
        2 => [
            'en' => 'Accountant',
            'km' => 'គណនេយ្យករ',
        ],
        3 => [
            'en' => 'Business Analyst',
            'km' => 'អ្នកវិភាគអាជីវកម្ម',
        ],
        4 => [
            'en' => 'Mobile App Developer',
            'km' => 'អ្នកអភិវឌ្ឍន៍កម្មវិធីទូរស័ព្ទ',
        ],
        5 => [
            'en' => 'Designer',
            'km' => 'អ្នករចនា',
        ],
        6 => [
            'en' => 'Software Developer',
            'km' => 'អ្នកអភិវឌ្ឍន៍កម្មវិធី',
        ],
        7 => [
            'en' => 'Director',
            'km' => 'នាយក',
        ],
        8 => [
            'en' => 'Financial Analyst',
            'km' => 'អ្នកវិភាគហិរញ្ញវត្ថុ',
        ],
        9 => [
            'en' => 'HR Specialist',
            'km' => 'អ្នកជំនាញធនធានមនុស្ស',
        ],
        10 => [
            'en' => 'Manager',
            'km' => 'អ្នកគ្រប់គ្រង',
        ],
        11 => [
            'en' => 'Marketing Specialist',
            'km' => 'អ្នកជំនាញទីផ្សារ',
        ],
        12 => [
            'en' => 'Project Manager',
            'km' => 'អ្នកគ្រប់គ្រងគម្រោង',
        ],
        13 => [
            'en' => 'Sales Representative',
            'km' => 'តំណាងផ្នែកលក់',
        ],
        14 => [
            'en' => 'Senior Software Developer',
            'km' => 'អ្នកអភិវឌ្ឍន៍កម្មវិធីជាន់ខ្ពស់',
        ],
        15 => [
            'en' => 'UI/UX Designer',
            'km' => 'អ្នករចនា UI/UX',
        ],
    ]
];
