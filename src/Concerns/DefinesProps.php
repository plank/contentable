<?php

namespace Plank\Contentable\Concerns;

use Plank\Contentable\Contracts\Layout;
use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Data\PropData;
use Plank\Contentable\Exceptions\LayoutDataException;
use Plank\Contentable\Exceptions\PropDataException;
use ReflectionClass;

trait DefinesProps
{
    protected static ?string $propsClass = null;

    /**
     * Create a layout instance
     */
    public static function from(string|Layoutable|Layout $model): static
    {
        if (is_string($model)) {
            return static::fromLayoutableClass($model);
        }

        if ($model instanceof Layoutable) {
            return static::fromLayoutable($model);
        }

        if ($model instanceof Layout) {
            return static::fromLayout($model);
        }

        throw LayoutDataException::create(get_class($model));
    }

    /**
     * Create layout data for a Layoutable Model
     */
    protected static function fromLayoutable(Layoutable $layoutable): static
    {
        return new static(
            key: $layoutable->layoutKey(),
            props: static::propDataClass()::from($layoutable)
        );
    }

    /**
     * Create layout data from a Model class
     *
     * @param  class-string<Layoutable>  $class
     */
    protected static function fromLayoutableClass(string $class): static
    {
        if (! is_a($class, Layoutable::class, true)) {
            throw LayoutDataException::create($class);
        }

        return new static(
            key: $class::indexLayoutKey(),
            props: static::propDataClass()::from($class),
        );
    }

    /**
     * Create layout data for a Layout Model instance
     */
    protected static function fromLayout(Layout $layout): static
    {
        return new static(
            key: $layout->layoutKey(),
            props: static::propDataClass()::from($layout)
        );
    }

    /**
     * Helper method to intelligently determine the props class being used
     */
    protected static function propDataClass(): string
    {
        if (static::$propsClass) {
            return static::$propsClass;
        }

        $refl = new ReflectionClass(static::class);
        $prop = $refl->getProperty('props');
        $type = $prop->getType();

        if (! $type || ! is_a($type->getName(), PropData::class, true)) {
            throw PropDataException::property(static::class);
        }

        static::$propsClass = $type->getName();

        return static::$propsClass;
    }
}
