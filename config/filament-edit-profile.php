<?php

return [
    'locale_column' => 'locale',
    'theme_color_column' => 'theme_color',
    'avatar_column' => 'avatar_url',
    'disk' => env('FILESYSTEM_DISK', 'public'),
    'visibility' => 'public', // or replace by filesystem disk visibility with fallback value
    'show_custom_fields' => true,
    'custom_fields' => [
        'username' => [
            'type' => 'text', // required
            'label' => 'Username', // required
            'placeholder' => 'Enter your username', // optional
            'id' => 'username', // optional
            'required' => true, // optional
            'rules' => [], // optional
            'hint_icon' => 'heroicon-o-information-circle', // optional
            'hint' => 'Select something unique', // optional
            'suffix_icon' => '', // optional
            'prefix_icon' => '', // optional
            'default' => '', // optional
            'column_span' => 'full', // optional
            'autocomplete' => false, // optional
        ],
    ]
];
