<?php

namespace Plank\Contentable\Contracts;

use Plank\Contentable\Enums\LayoutMode;

interface Layout
{
    /**
     * The flavor the application's FE is using (blade or inertia)
     */
    public static function mode(): LayoutMode;

    /**
     * The folder in the filesystem where the layout files are stored
     */
    public static function folder(): string;

    /**
     * The extension for the layout files
     */
    public static function extension(): string;

    /**
     * The separator for the layout keys:
     * . for Blade
     * / for all Inertia flavors
     */
    public static function separator(): string;

    /**
     * Get the Layout key column
     */
    public static function getLayoutKeyColumn(): string;

    /**
     * Get the Layout name column
     */
    public static function getNameColumn(): string;

    /**
     * Get the Layout type column
     */
    public static function getTypeColumn(): string;

    /**
     * Get the Layoutable column
     */
    public static function getLayoutableColumn(): string;
}
