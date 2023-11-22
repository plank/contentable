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
        $layout = static::customizeableLayout()
            ? $this->relatedLayout ?? static::showLayout()
            : static::showLayout();

        if ($layout === null) {
            throw MissingLayoutException::show(static::class, $this->getKey());
        }

        return $layout;
    }

    /**
     * Get the default show layout for the Model
     */
    public static function showLayout(): ?Layout
    {
        $layoutModel = static::layoutModel();

        return $layoutModel::query()
                ->where($layoutModel::getLayoutKeyColumn(), static::showLayoutKey())
                ->first();
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

    public static function layouts(): Collection
    {
        if (!static::customizeableLayout()) {
            return new Collection();
        }

        $layoutModel = static::layoutModel();

        return $layoutModel::query()
            ->where($layoutModel::getLayoutableColumn(), static::layoutKey())
            ->when(static::globalLayouts(), function (Builder $query) use ($layoutModel) {
                $query->orWhere(function (Builder $query) use ($layoutModel) {
                    $query->where($layoutModel::getTypeColumn(), LayoutType::Global)
                        ->whereNotIn($layoutModel::getLayoutKeyColumn(), static::excludedLayouts());
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
        $layoutModel = static::layoutModel();

        return (new $layoutModel)->getKeyName();
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
     * The default index layout key of the Model
     */
    public static function showLayoutKey(): string
    {
        $layoutModel = static::layoutModel();

        return str(static::layoutKey())
            ->append($layoutModel::separator())
            ->append(static::showKey());
    }

    /**
     * The key we will use for the default index layouts
     */
    protected static function showKey(): string
    {
        $layoutModel = static::layoutModel();

        return match ($layoutModel::mode()) {
            LayoutMode::Blade => 'show',
            default => 'Show',
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
    protected static function globalLayouts(): bool
    {
        if (property_exists(static::class, 'globalLayouts')) {
            return static::$globalLayouts;
        }

        return true;
    }

    /**
     * Exclude specific layouts by their keys
     */
    protected static function excludedLayouts(): array
    {
        if (property_exists(static::class, 'excludedLayouts')) {
            return static::$excludedLayouts;
        }

        return [];
    }

    /**
     * Exclude specific layouts by their keys
     */
    protected static function customizeableLayout(): bool
    {
        if (property_exists(static::class, 'customizeableLayout')) {
            return static::$customizeableLayout;
        }

        return true;
    }
}
