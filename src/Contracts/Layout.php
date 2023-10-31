<?php

namespace Plank\Contentable\Contracts;

interface Layout
{
    /**
     * Get the layout key
     */
    public function layoutKey(): string;

    /**
     * Get the name of the layout key attribute
     */
    public static function getLayoutKeyName(): string;
}
