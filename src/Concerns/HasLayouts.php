<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Plank\Contentable\Contracts\Layout;
use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Enums\LayoutMode;
use Plank\Contentable\Enums\LayoutType;
use Plank\Contentable\Exceptions\MissingLayoutException;

/**
 * @mixin Model
 * @mixin Layoutable
 */
trait HasLayouts
{
    public function layout(): Layout
    {
        if ($this->relatedLayout === null) {
            throw MissingLayoutException::show(static::class, $this->getKey());
        }

        return $this->relatedLayout;
    }

    public static function indexLayout(): Layout
    {
        $layoutModel = static::layoutModel();

        $layout = $layoutModel::query()
            ->where($layoutModel::getLayoutKeyColumn(), static::indexLayoutKey())
            ->first();

        if ($layout === null) {
            throw MissingLayoutException::index(static::class);
        }

        return $layout;
    }

    public function layouts(): Collection
    {
        $layoutModel = static::layoutModel();

        return $layoutModel::query()
            ->where($layoutModel::getLayoutableColumn(), static::layoutKey())
            ->when($this->globalLayouts(), function (Builder $query) use ($layoutModel) {
                $query->orWhere(function (Builder $query) use ($layoutModel) {
                    $query->where($layoutModel::getTypeColumn(), LayoutType::Global)
                        ->whereNotIn($layoutModel::getLayoutKeyColumn(), $this->excludedLayouts());
                });
            })
            ->get()
            ->sortBy($layoutModel::getNameColumn());
    }

    /**
     * The default implementation ships as a parent relationship
     *
     * @return BelongsTo
     */
    public function relatedLayout()
    {
        /** @var class-string<Layout&Model> $layoutModel */
        $layoutModel = config('contentable.layouts.model');

        return $this->belongsTo(
            $layoutModel,
            $this->layoutForeignKey(),
            $this->layoutOwnerKey(),
        );
    }

    /**
     * The foreign key for the layout relationship
     */
    protected function layoutForeignKey(): string
    {
        return 'layout_id';
    }

    /**
     * The primary key on the layouts table
     */
    protected function layoutOwnerKey(): string
    {
        return 'id';
    }

    /**
     * The key of the layout files for this Layoutable
     */
    public static function layoutKey(): string
    {
        $layoutModel = static::layoutModel();

        $name = str(class_basename(static::class))
            ->plural()
            ->snake();

        return match ($layoutModel::mode()) {
            LayoutMode::Blade => $name->lower(),
            default => $name->studly(),
        };
    }

    /**
     * The default index layout key of the Model
     */
    public static function indexLayoutKey(): string
    {
        $layoutModel = static::layoutModel();

        return str(static::layoutKey())
            ->append($layoutModel::separator())
            ->append(static::indexKey());
    }

    /**
     * The key we will use for the default index layouts
     */
    protected static function indexKey(): string
    {
        $layoutModel = static::layoutModel();

        return match ($layoutModel::mode()) {
            LayoutMode::Blade => 'index',
            default => 'Index',
        };
    }

    /**
     * @return class-string<Layout&Model>
     */
    protected static function layoutModel(): string
    {
        return config()->get('contentable.layouts.model');
    }

    /**
     * Determine if the Layoutable uses global layouts
     */
    protected function globalLayouts(): bool
    {
        if (property_exists($this, 'globalLayouts')) {
            return $this->globalLayouts;
        }

        return true;
    }

    /**
     * Exclude specific layouts by their keys
     */
    protected function excludedLayouts(): array
    {
        if (property_exists($this, 'excludedLayouts')) {
            return $this->excludedLayouts;
        }

        return [];
    }
}
