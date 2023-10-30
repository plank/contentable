<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Plank\Contentable\Contracts\Layout as LayoutContract;
use Plank\Contentable\Data\AbstractLayoutData;
use Plank\Contentable\Exceptions\ForcedLayoutException;

class Layout extends Model implements LayoutContract
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * The key on the model which identifies the layout
     */
    const LAYOUT_KEY = 'identifier';

    /**
     * {@inheritDoc}
     */
    public static function forced(string $key): static
    {
        $layout = static::where(static::LAYOUT_KEY, $key)->first();

        if (! $layout) {
            throw ForcedLayoutException::create($key);
        }

        return $layout;
    }

    /**
     * {@inheritDoc}
     */
    public function data(): AbstractLayoutData
    {
        $class = $this->dataClass();

        return $class::from($this);
    }

    /**
     * {@inheritDoc}
     */
    public function dataClass(): ?string
    {
        return static::dataClassFromKey($this->layoutKey());
    }

    /**
     * {@inheritDoc}
     */
    public static function dataClassFromKey(string $key): ?string
    {
        $basename = str($key)
            ->rtrim('\\')
            ->lower()
            ->explode('.')
            ->tap(function (Collection $parts) {
                $first = $parts->shift();
                $parts->prepend((string) str($first)->singular());

                if ($parts->last() === 'show') {
                    $parts->pop();
                }

                return $parts;
            })
            ->map(fn (string $part) => (string) str($part)->studly())
            ->push('LayoutData')
            ->join('');

        /** @var class-string<AbstractLayoutData> $class */
        $class = config('contentable.layouts.namespace').'\\'.$basename;

        if (class_exists($class)) {
            return $class;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function props(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function layoutKey(): string
    {
        return $this->getAttribute($this->getLayoutKeyName());
    }

    /**
     * {@inheritDoc}
     */
    public function getLayoutKeyName(): string
    {
        return static::LAYOUT_KEY;
    }
}
