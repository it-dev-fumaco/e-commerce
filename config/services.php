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
        'client_id' => '435536724607670',
        'client_secret' => '983cd95dca020ece7f82b82c2f97c826',
        'redirect' => 'https://fumaco.com/auth/facebook/callback',
    ],

    'google' => [
        'client_id' => '794522382369-0pdu3n5j2ofohbae2hcbtgtfqiu898a2.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://fumaco.com/auth/google/callback',
    ],

    'linkedin' => [
        'client_id' => 'id',
        'client_secret' => 'secret',
        'redirect' => 'https://fumaco.com/auth/linkedin/callback'

    ],

];
