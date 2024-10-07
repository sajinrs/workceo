<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Company::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'onesignal' => [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'rest_api_key' => env('ONESIGNAL_REST_API_KEY')
    ],

    'currency' => [
        'name' => env('CURRENCY_NAME', 'usd'),
        'symbol' => env('CURRENCY_SYMBOL', '$')
    ],

    'pubbly' => [
        'api_key' => env('PUBBLY_API_KEY'),
        'secret_key' => env('PUBBLY_SECRET_KEY')
     ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => env('FACEBOOK_URL'),
    ],
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_URL'),
    ],
    'zoho' => [
        'zoho_org_id'        => env('ZOHO_ORGANIZATION_ID'),
        'zoho_client_id'     => env('ZOHO_CLIENT_ID'),
        'zoho_client_secret' => env('ZOHO_CLIENT_SECRET'),
        'zoho_refresh_token' => env('ZOHO_REFRESH_TOKEN'),
        'zoho_redirect_uri'  => env('ZOHO_REDIRECT_URI'),
        'default_plan_code'  => env('ZOHO_DEFAULT_PLAN_CODE')

    ]

];
