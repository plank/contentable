<?php

namespace Plank\Contentable\Contracts;

interface Layout
{
    /**
     * Get the Layout key
     */
    public function layoutKey(): string;

    /**
     * Get the name of the Layout key attribute
     */
    public static function getLayoutKeyName(): string;

    /**
     * Retrieve the Layout's Blade template name
     */
    public function bladeTemplate(): string;

    /**
     * Retrieve the Layout's Inertia component name
     */
    public function inertiaComponent(): string;
}
