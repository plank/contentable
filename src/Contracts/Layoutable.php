<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Collection;

/**
 * @property string $layout
 */
interface Layoutable
{
    /**
     * Retrieve the layout for the model
     */
    public function layout(): Layout;

    /**
     * Allow the class to define a layout for its index
     */
    public static function indexLayout(): Layout;

    /**
     * Get the Layout options as Key Value pairs
     *
     * @return Collection<Layout>
     */
    public static function layouts(): Collection;

    /**
     * Define the key which is used to identify the Index layout for the Layoutable
     */
    public static function indexLayoutKey(): string;

    /**
     * Get the layout key for the class
     */
    public static function layoutKey(): string;
}
