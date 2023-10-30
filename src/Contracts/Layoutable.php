<?php

namespace Plank\Contentable\Contracts;

use Plank\Contentable\Data\AbstractLayoutData;
use Plank\Contentable\Exceptions\LayoutDataNotFoundException;

interface Layoutable
{
    /**
     * Retrieve a related Layout if one exists
     */
    public function layout(): ?Layout;

    /**
     * Get the layout data for the model
     * 
     * @throws LayoutDataNotFoundException
     */
    public function layoutData(): AbstractLayoutData;

    /**
     * Allow our Models to define a genric Layout Data class as a fallback for when
     * no custom Layout is defined.
     *
     * @return class-string<AbstractLayoutData>|null
     */
    public function layoutDataClass(): ?string;

    /**
     * Determine the layout key for the model
     */
    public function layoutKey(): string;

    /**
     * Allow the model to define props for its layout
     */
    public function props(): array;

    /**
     * Allow the model's class to define a layout for its index
     */
    public static function indexLayout(): ?Layout;

    /**
     * Allow the model's class to define a layout for its index
     * 
     * @throws LayoutDataNotFoundException
     */
    public static function indexLayoutData(): AbstractLayoutData;

    /**
     * Allow the model's class to define an index layout
     *
     * @return class-string<AbstractLayoutData>|null
     */
    public static function indexLayoutDataClass(): ?string;

    /**
     * Determine the layout key for the model
     */
    public static function indexLayoutKey(): string;

    /**
     * Allow the model's class to define props for its index layout
     */
    public static function indexProps(): array;
}
