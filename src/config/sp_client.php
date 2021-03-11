<?php

return [
    'base_uri' => env('SP_BASE_URI', 'http://localhost'),
    'token' => env('SP_TOKEN', 'api_token'),
    'uri' => [
        'campaigns' => "/api/v1/campaigns",
        'tags' => "/api/v1/tags",
        'subscribers' => "/api/v1/subscribers",
    ]
];
