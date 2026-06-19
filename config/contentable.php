<?php

use Plank\Contentable\Enums\LayoutMode;
use Plank\Contentable\Models\Content;
use Plank\Contentable\Models\Layout;

return [
    'content' => [
        'model' => Content::class,
    ],
    'layouts' => [
        'folder' => 'layouts',
        'mode' => LayoutMode::Blade,
        'model' => Layout::class,
        'sync' => [
            'excluded' => [],
        ],
    ],
    'cache' => [
        'ttl' => 10800,
    ],
];
