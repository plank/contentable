<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Contracts\Layout as LayoutContract;

class Layout extends Model implements LayoutContract
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * {@inheritDoc}
     */
    public function layoutKey(): string
    {
        return $this->getAttribute(static::getLayoutKeyName());
    }

    /**
     * {@inheritDoc}
     */
    public static function getLayoutKeyName(): string
    {
        return 'identifier';
    }

    /**
     * {@inheritDoc}
     */
    public function bladeTemplate(): string
    {
        $key = str($this->layoutKey())
            ->prepend('layouts.')
            ->replace('/', '.')
            ->explode('.')
            ->map(fn ($part) => (string) str($part)->snake())
            ->implode('.');

        return $key;
    }

    /**
     * {@inheritDoc}
     */
    public function inertiaComponent(): string
    {
        $key = str($this->layoutKey())
            ->trim('/')
            ->replace('/', '.')
            ->explode('.')
            ->map(fn ($part) => (string) str($part)->studly())
            ->implode('/');

        return $key;
    }
}
