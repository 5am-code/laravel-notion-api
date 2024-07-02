<?php

return [
    /**
     * The default Notion API version to use.
     */
    'version' => 'v1',

    /**
     * Your Notion API token.
     */
    'notion-api-token' => env('NOTION_API_TOKEN', ''),

    'version-date' => env('NOTION_API_VERSION_DATE', '2021-05-13'),
];
