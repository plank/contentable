<?php

namespace Plank\Contentable\Data;

use Illuminate\Contracts\Support\Arrayable;
use Plank\Contentable\Contracts\Layout;
use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Exceptions\PropDataException;
use ReflectionClass;

class PropData implements Arrayable
{
    public function __construct(
        protected array $props = [],
    ) {
    }

    /**
     * Create a layout instance
     */
    public static function from(string|Layout|Layoutable $model): static
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

        throw PropDataException::create(get_class($model));
    }

    /**
     * Create layout data for a Layoutable Model instance
     */
    public static function fromLayoutable(Layoutable $layoutable): static
    {
        return new static(array_merge(
            $layoutable->props(),
            $layoutable->layout()?->props() ?? []
        ));
    }

    /**
     * Create layout data for a layoutable Model
     *
     * @param  class-string<Layoutable>  $class
     */
    public static function fromLayoutableClass(string $class): static
    {
        if (! is_a($class, Layoutable::class, true)) {
            throw PropDataException::create($class);
        }

        return new static(array_merge(
            $class::indexProps(),
            $class::indexLayout()?->props() ?? []
        ));
    }

    /**
     * Create layout data for a Layout Model instance
     */
    public static function fromLayout(Layout $layout): static
    {
        return new static($layout->props());
    }

    /**
     * Get an array representation of the prop data
     */
    public function toArray()
    {
        $data = $this->props;

        $reflector = new ReflectionClass($this);

        foreach ($reflector->getProperties() as $property) {
            if (! $property->isPublic() || $property->getName() === 'props') {
                continue;
            }

            $data[$property->getName()] = $property->getValue($this);
        }

        return $data;
    }

    /**
     * Dynamically retrieve the props
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->props)) {
            return $this->props[$key];
        }
        
        return null;
    }

    /**
     * Dynamically set the props.
     *
     * @param  mixed  $value
     * @return void
     */
    public function __set(string $key, $value)
    {
        $this->props[$key] = $value;
    }
}
