<?php

return [
    'locale_column' => 'locale',
    'theme_color_column' => 'theme_color',
    'avatar_column' => 'avatar_url',
    'disk' => env('FILESYSTEM_DISK', 'public'),
    'visibility' => 'public', // or replace by filesystem disk visibility with fallback value
    'show_custom_fields' => true,
    'custom_fields' => [
        'primary_contact_number' => [
            'type' => 'text', // required
            'label' => 'Primary Contact Number', // required
            'placeholder' => 'Enter your primary contact number', // optional
            'id' => 'primary_contact_number', // optional
            'required' => true, // optional
            'rules' => [], // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'suffix_icon' => '', // optional
            'prefix_icon' => '', // optional
            'default' => '', // optional
            'column_span' => 'full', // optional
            'autocomplete' => false, // optional
        ],
        'secondary_contact_number' => [
            'type' => 'text', // required
            'label' => 'Secondary Contact Number', // required
            'placeholder' => 'Enter your secondary contact number', // optional
            'id' => 'secondary_contact_number', // optional
            'required' => false, // optional
            'rules' => [], // optional
            'hint_icon' => '', // optional
            'hint' => '', // optional
            'suffix_icon' => '', // optional
            'prefix_icon' => '', // optional
            'default' => '', // optional
            'column_span' => 'full', // optional
            'autocomplete' => false, // optional
        ],
    ]
];
