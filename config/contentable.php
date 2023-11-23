<?php

return [
    'content' => [
        'model' => \Plank\Contentable\Models\Content::class,
    ],
    'layouts' => [
        'folder' => 'layouts',
        'mode' => \Plank\Contentable\Enums\LayoutMode::Blade,
        'model' => \Plank\Contentable\Models\Layout::class,
        'sync' => [
            'excluded' => [],
        ]
    ],
    'cache' => [
        'ttl' => 10800,
    ],
];
