<?php

namespace Plank\Contentable\Tests\Helper\Data;

use Plank\Contentable\Tests\Helper\Models\MenuItem;

class MenuItemData
{
    public function __construct(
        public string $title,
        public string $url,
    ) {
    }

    public static function fromMenuItem(MenuItem $item)
    {
        return new static(
            $item->title,
            $item->url,
        );
    }
}
