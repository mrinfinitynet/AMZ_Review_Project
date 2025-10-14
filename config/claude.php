<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Claude Server URL
    |--------------------------------------------------------------------------
    |
    | This is the URL of your Claude server API. When set, the application
    | will use API calls to manage data instead of local database.
    | Leave empty to use local database mode.
    |
    */

    'url' => env('CLAUDE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Claude API Token
    |--------------------------------------------------------------------------
    |
    | This token is used to authenticate API requests to the Claude server.
    | Make sure to keep this token secure and never commit it to version control.
    |
    */

    'api_token' => env('CLAUDE_API_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | API Mode
    |--------------------------------------------------------------------------
    |
    | Determines if the application is running in API mode (remote database)
    | or local mode (local database). This is automatically determined based
    | on whether CLAUDE_URL is set.
    |
    */

    'enabled' => !empty(env('CLAUDE_URL', '')),

    /*
    |--------------------------------------------------------------------------
    | API Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for API requests to the Claude server.
    |
    */

    'timeout' => env('CLAUDE_API_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    |
    | Define the API endpoints for different resources.
    |
    */

    'endpoints' => [
        'admins' => '/api/admins',
        'projects' => '/api/projects',
        'accounts' => '/api/accounts',
        'posts' => '/api/posts',
    ],

];
