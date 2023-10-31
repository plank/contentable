<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Contracts\Layout as LayoutContract;
use Plank\Contentable\Contracts\Layoutable;

/**
 * @mixin Model
 * @mixin Layoutable
 */
trait HasLayouts
{
    /**
     * {@inheritDoc}
     */
    public function layout(): LayoutContract
    {
        /** @var class-string<LayoutContract&Model> $model */
        $layoutModel = config('contentable.layouts.model');

        return $layoutModel::query()
            ->where($layoutModel::getLayoutKeyName(), $this->layoutKey())
            ->firstOrFail();
    }

    /**
     * {@inheritDoc}
     */
    public function layoutKey(): string
    {
        return str(class_basename($this))
            ->plural()
            ->lower()
            ->append('.show');
    }

    /**
     * {@inheritDoc}
     */
    public static function indexLayout(): LayoutContract
    {
        /** @var class-string<LayoutContract&Model> $layoutModel */
        $layoutModel = config('contentable.layouts.model');

        return $layoutModel::query()
            ->where($layoutModel::getLayoutKeyName(), static::indexLayoutKey())
            ->first();
    }

    /**
     * {@inheritDoc}
     */
    public static function indexLayoutKey(): string
    {
        return str(class_basename(static::class))->plural()->lower()->append('.index');
    }
}
