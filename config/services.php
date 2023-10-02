<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => 'https://test301.fumaco.com/login/facebook/callback',
    ],
    

    'google' => [
        // 'client_id' => '794522382369-0pdu3n5j2ofohbae2hcbtgtfqiu898a2.apps.googleusercontent.com',
        // 'client_secret' => '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',
        'client_id' => '461557894965-g8rtlfv4ngmnsc6g19v93abuqaok09id.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://test301.fumaco.com/auth/google/callback',
    ],

    'linkedin' => [
        'client_id' => '86qae63bbvyy22',
        'client_secret' => '5bC4dey0d7vAqwgH',
        'redirect' => 'https://www.fumaco.com/auth/linkedin/callback'
    ],

];
