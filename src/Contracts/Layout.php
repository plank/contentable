<?php

namespace Plank\Contentable\Contracts;

use Plank\Contentable\Data\AbstractLayoutData;
use Plank\Contentable\Exceptions\ForcedLayoutException;
use Plank\Contentable\Exceptions\LayoutDataNotFoundException;

interface Layout
{
    /**
     * Get a layout by its key and throw an exception if it does not exist
     *
     * @throws ForcedLayoutException
     */
    public static function forced(string $key): Layout;

    /**
     * Get the data for the Layout
     *
     * @throws LayoutDataNotFoundException
     */
    public function data(): AbstractLayoutData;

    /**
     * Get the data class for the Layout
     *
     * @return class-string<AbstractLayoutData>|null
     */
    public function dataClass(): ?string;

    /**
     * Get the data class for a layout key. Format:
     *    "posts.show" => PostLayoutData
     *    "posts.index" => PostIndexLayoutData
     *    "menu_items.show" => MenuItemLayoutData
     *    "menu_items.index" => MenuItemIndexLayoutData
     *    "app" => AppLayoutData
     * 
     * @return class-string<AbstractLayoutData>|null
     */
    public static function dataClassFromKey(string $key): ?string;

    /**
     * Get the prop data from the layout
     */
    public function props(): array;

    /**
     * Get the layout key
     */
    public function layoutKey(): string;

    /**
     * Get the name of the layout key attribute
     */
    public function getLayoutKeyName(): string;
}
