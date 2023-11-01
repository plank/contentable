<?php

namespace Plank\Contentable\Contracts;

interface Layoutable
{
    /**
     * Retrieve a related Layout if one exists
     */
    public function layout(): Layout;

    /**
     * Determine the layout key for the model
     */
    public function layoutKey(): string;

    /**
     * Allow the model's class to define a layout for its index
     */
    public static function indexLayout(): Layout;

    /**
     * Determine the layout key for the model
     */
    public static function indexLayoutKey(): string;
}
