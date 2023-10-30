<?php

namespace Plank\Contentable\Tests\Helper\Data;

use Plank\Contentable\Tests\Helper\Models\Menu;

class MenuData
{
    /**
     * @param  array<MenuItemData>  $items
     */
    public function __construct(
        public string $title,
        public array $items,
    ) {
    }

    public static function fromMenu(Menu $menu)
    {
        return new static(
            $menu->title,
            $menu->items->map(fn ($item) => MenuItemData::fromMenuItem($item))->toArray(),
        );
    }
}
