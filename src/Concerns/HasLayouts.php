<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Plank\Contentable\Contracts\Layout as LayoutContract;
use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Contracts\Template as TemplateContract;
use Plank\Contentable\Data\AbstractLayoutData;
use Plank\Contentable\Exceptions\LayoutDataNotFoundException;
use Plank\Contentable\Models\Template;

/**
 * @mixin Model
 * @mixin Layoutable
 *
 * @property-read TemplateContract|null $templateModel
 */
trait HasLayouts
{
    /**
     * {@inheritDoc}
     */
    public function layout(): ?LayoutContract
    {
        return $this->templateModel?->layout();
    }

    /**
     * {@inheritDoc}
     */
    public function layoutData(): AbstractLayoutData
    {
        $class = $this->layout()?->dataClass()
            ?? $this->layoutDataClass()
            ?? config('contentable.layouts.data');

        if (! class_exists($class) || ! is_a($class, AbstractLayoutData::class, true)) {
            throw LayoutDataNotFoundException::create($this->layoutKey());
        }

        return $class::from($this);
    }

    /**
     * {@inheritDoc}
     */
    public function layoutDataClass(): ?string
    {
        /** @var class-string<LayoutContract&Model> $model */
        $model = config('contentable.layouts.model');

        return $model::dataClassFromKey($this->layoutKey());
    }

    /**
     * {@inheritDoc}
     */
    public function layoutKey(): string
    {
        return $this->layout()?->layoutKey()
            ?? $this->defaultLayoutKey();
    }

    /**
     * {@inheritDoc}
     */
    public function defaultLayoutKey(): string
    {
        return str(class_basename($this))
            ->plural()
            ->lower()
            ->append('.show');
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
    public static function indexLayout(): ?LayoutContract
    {
        /** @var class-string<LayoutContract&Model> $layoutModel */
        $layoutModel = config('contentable.layouts.model');

        return $layoutModel::query()
            ->where('identifier', static::defaultIndexLayoutKey())
            ->first();
    }

    /**
     * {@inheritDoc}
     */
    public static function indexLayoutData(): AbstractLayoutData
    {
        $class = static::indexLayoutDataClass()
            ?? config('contentable.layouts.data');

        if (! class_exists($class) || ! is_a($class, AbstractLayoutData::class, true)) {
            throw LayoutDataNotFoundException::create(static::indexLayoutKey());
        }

        return $class::from(static::class);
    }

    /**
     * {@inheritDoc}
     */
    public static function indexLayoutDataClass(): ?string
    {
        /** @var class-string<LayoutContract&Model> $model */
        $model = config('contentable.layouts.model');

        return $model::dataClassFromKey(static::indexLayoutKey());
    }

    /**
     * {@inheritDoc}
     */
    public static function indexLayoutKey(): string
    {
        return static::indexLayout()?->layoutKey()
            ?? static::defaultIndexLayoutKey();
    }

    /**
     * {@inheritDoc}
     */
    public static function indexProps(): array
    {
        return [];
    }

    /**
     * Determine the default identifier for an index layout of this model. The convention is
     * that is this was a Post model the identifier would be "posts.index".
     */
    protected static function defaultIndexLayoutKey(): string
    {
        return str(class_basename(static::class))->plural()->lower()->append('.index');
    }

    /**
     * Get the intermediary template Model
     */
    public function templateModel(): MorphOne
    {
        return $this->morphOne(Template::class, 'layoutable');
    }
}
