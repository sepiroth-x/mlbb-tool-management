<?php

return [
    'name' => 'MLBB Tool Management',
    'description' => 'Professional MLBB esports tournament management system',
    
    /*
    |--------------------------------------------------------------------------
    | Hero Data Source
    |--------------------------------------------------------------------------
    |
    | Determines whether to use database or JSON file for hero data
    | Options: 'database', 'json'
    |
    */
    'hero_data_source' => env('MLBB_HERO_SOURCE', 'json'),
    
    /*
    |--------------------------------------------------------------------------
    | Real-time Update Method
    |--------------------------------------------------------------------------
    |
    | Method for real-time overlay updates
    | Options: 'polling', 'websocket', 'pusher'
    |
    */
    'realtime_method' => env('MLBB_REALTIME_METHOD', 'polling'),
    
    /*
    |--------------------------------------------------------------------------
    | Polling Interval
    |--------------------------------------------------------------------------
    |
    | Interval in milliseconds for polling updates (if using polling method)
    |
    */
    'polling_interval' => env('MLBB_POLLING_INTERVAL', 2000),
    
    /*
    |--------------------------------------------------------------------------
    | WebSocket Configuration
    |--------------------------------------------------------------------------
    |
    | WebSocket server configuration (if using websocket method)
    |
    */
    'websocket' => [
        'host' => env('MLBB_WS_HOST', 'localhost'),
        'port' => env('MLBB_WS_PORT', 6001),
        'scheme' => env('MLBB_WS_SCHEME', 'ws'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Cache settings for hero data and match states
    |
    */
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour
        'prefix' => 'mlbb_',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Match Configuration
    |--------------------------------------------------------------------------
    |
    | Default settings for matches
    |
    */
    'match' => [
        'max_bans_per_team' => 3,
        'max_picks_per_team' => 5,
        'phases' => ['ban', 'pick'],
    ],
];
