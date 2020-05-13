<?php

return [

    'comment_table' => env('CACKLE_COMMENT_TABLE', 'cackle_comments'),

    'channel_table' => env('CACKLE_CHANNEL_TABLE', 'cackle_channels'),

    'channel_list_url' => env('CACKLE_CHANNEL_LIST_URL', 'http://cackle.me/api/3.0/comment/chan/list.json'),

    'comment_list_url' => env('CACKLE_COMMENT_LIST_URL', 'http://cackle.me/api/3.0/comment/list.json'),

    'request_interval' => env('CACKLE_REQUEST_INTERVAL', 10),

    'parameters' => [

        'id' => env('CACKLE_SITE_ID', ''),

        'siteApiKey' => env('CACKLE_SITE_API_KEY', ''),

        'accountApiKey' => env('CACKLE_ACCOUNT_API_KEY', ''),
    ],
];
