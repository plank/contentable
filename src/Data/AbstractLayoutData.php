<?php

namespace Plank\Contentable\Data;

use Illuminate\Contracts\Support\Arrayable;
use Plank\Contentable\Contracts\Layout;
use Plank\Contentable\Contracts\Layoutable;

/**
 * @property-read string $component The layout's Inertia component name
 * @property-read string $template The layout's Blade template name
 */
abstract class AbstractLayoutData implements Arrayable
{
    public function __construct(
        public string $key
    ) {
    }

    /**
     * Get a layout by its key and throw an exception if it does not exist
     */
    abstract public static function from(string|Layoutable|Layout $model): static;

    /**
     * Convert the layout data to an array
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'props' => $this->props->toArray(),
        ];
    }

    /**
     * Dynamically retrieve the props
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key === 'component') {
            return $this->inertiaComponent($this->key);
        }

        if ($key === 'template') {
            return $this->bladeTemplate($this->key);
        }

        if (! property_exists($this, 'props')) {
            if ($key === 'props') {
                return new PropData([]);
            }

            return null;
        }

        return $this->props->$key;
    }

    /**
     * Retrieve the layout's Blade template name
     */
    protected function bladeTemplate(string $key): string
    {
        $key = str($key)
            ->prepend('layouts.')
            ->replace('/', '.')
            ->explode('.')
            ->map(fn ($part) => (string) str($part)->snake())
            ->implode('.');

        if ($namespace = config('contentable.layouts.blade_namespace')) {
            $key = $namespace.'::'.$key;
        }

        return $key;
    }

    /**
     * Retrieve the layout's Inertia component name
     */
    protected function inertiaComponent(string $key): string
    {
        $key = str($key)
            ->trim('/')
            ->replace('/', '.')
            ->explode('.')
            ->map(fn ($part) => (string) str($part)->studly())
            ->implode('/');

        return $key;
    }
}
