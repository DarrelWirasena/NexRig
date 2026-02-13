<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Bagian 'cloud' ini WAJIB ADA agar library tidak error.
    |
    */
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key'    => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'url'        => env('CLOUDINARY_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Preset
    |--------------------------------------------------------------------------
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', 'ml_default'),

    /*
    |--------------------------------------------------------------------------
    | Global URL
    |--------------------------------------------------------------------------
    */
    'cloud_url' => env('CLOUDINARY_URL'),
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
];