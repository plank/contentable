<?php

return [

    'model' => \Plank\Contentable\Models\Content::class,
    'cache' => [
        'ttl' => 10800
    ],
    'layouts' => [
        'model' => \Plank\Contentable\Models\Layout::class,
    ],
];
