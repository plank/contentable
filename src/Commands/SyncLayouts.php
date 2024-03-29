<?php

namespace Plank\Contentable\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Stringable;
use Plank\Contentable\Contracts\Layout;
use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Enums\LayoutType;
use SplFileInfo;

class SyncLayouts extends Command
{
    protected $signature = 'contentable:sync';

    protected $description = 'Ensure defined layout files exist as Layout Models.';

    public function handle(): void
    {
        foreach ($this->globalKeys() as $key) {
            $this->ensureGlobalLayoutExists($key);
        }

        foreach ($this->layoutableKeys() as $key) {
            $this->ensureModelLayoutExists($key);
        }
    }

    /**
     * Get the Layout keys available to all Layoutables
     */
    protected function globalKeys(): array
    {
        $layoutModel = static::layoutModel();
        $excluded = config()->get('contentable.layouts.sync.excluded');

        return Collection::wrap(File::files($layoutModel::folder()))
            ->map(function (SplFileInfo $layout) use ($layoutModel) {
                return $layout->getBasename($layoutModel::extension());
            })
            ->reject(function (string $key) use ($excluded) {
                return str($key)->contains($excluded);
            })
            ->values()
            ->all();
    }

    /**
     * Get the class specific Layout keys
     */
    protected function layoutableKeys(): array
    {
        $layoutModel = static::layoutModel();
        $keys = [];

        foreach (File::directories($layoutModel::folder()) as $path) {
            if ($this->pathIsExcludedByWildcard($path)) {
                continue;
            }

            $keys = array_merge($keys, $this->keysForPath($path));
        }

        return $keys;
    }

    protected function pathIsExcludedByWildcard(string $path): bool
    {
        $layoutModel = static::layoutModel();

        $excluded = Collection::wrap(config()->get('contentable.layouts.sync.excluded'))
            ->filter(fn (string $wildcard) => str($wildcard)->endsWith('*'))
            ->map(fn (string $wildcard) => str($wildcard)->before($layoutModel::separator()));

        return str($path)
            ->after($layoutModel::folder().DIRECTORY_SEPARATOR)
            ->before(DIRECTORY_SEPARATOR)
            ->contains($excluded);
    }

    protected function keysForPath(string $path): array
    {
        $excluded = config()->get('contentable.layouts.sync.excluded');
        $layoutModel = static::layoutModel();

        $key = (string) str($path)->afterLast(DIRECTORY_SEPARATOR);

        return Collection::wrap(File::files($path))
            ->map(function (SplFileInfo $layout) use ($layoutModel, $key) {
                return str($key)
                    ->append($layoutModel::separator())
                    ->append($layout->getBasename($layoutModel::extension()));
            })
            ->reject(fn (Stringable $key) => $key->contains($excluded))
            ->map(fn (Stringable $key) => (string) $key)
            ->values()
            ->all();
    }

    protected function ensureGlobalLayoutExists(string $key): void
    {
        $layoutModel = static::layoutModel();

        $layout = $layoutModel::query()
            ->where($layoutModel::getLayoutKeyColumn(), $key)
            ->first();

        if ($layout !== null) {
            return;
        }

        $layoutModel::query()->create([
            $layoutModel::getLayoutKeyColumn() => $key,
            $layoutModel::getNameColumn() => $this->globalKeyToName($key),
            $layoutModel::getTypeColumn() => LayoutType::Global,
        ]);
    }

    protected function ensureModelLayoutExists(string $key): void
    {
        $layoutModel = static::layoutModel();

        $layout = $layoutModel::query()
            ->where($layoutModel::getLayoutKeyColumn(), $key)
            ->first();

        if ($layout !== null) {
            return;
        }

        [$layoutableKey, $layoutKey] = explode($layoutModel::separator(), $key);

        $type = match (strtolower($layoutKey)) {
            'index' => LayoutType::Index,
            'show' => LayoutType::Show,
            default => LayoutType::Custom,
        };

        $layoutModel::query()->create([
            $layoutModel::getLayoutKeyColumn() => $key,
            $layoutModel::getNameColumn() => $this->layoutableKeyToName($layoutableKey, $layoutKey, $type),
            $layoutModel::getTypeColumn() => $type,
            $layoutModel::getLayoutableColumn() => $layoutableKey,
        ]);
    }

    /**
     * Create a layout name for the given key
     */
    protected function globalKeyToName(string $key): string
    {
        $layoutModel = static::layoutModel();

        return str($key)
            ->replace($layoutModel::separator(), '_')
            ->snake()
            ->replace('_', ' ')
            ->title();
    }

    /**
     * Create a layout name for the given key
     *
     * @param  class-string<Layoutable>  $layoutable
     */
    protected function layoutableKeyToName(string $layoutableKey, string $layoutKey, LayoutType $type): string
    {
        $layoutModel = static::layoutModel();

        $modelName = str($layoutableKey)
            ->singular()
            ->snake()
            ->replace('_', ' ')
            ->title();

        $name = str($layoutKey)
            ->replace($layoutModel::separator(), '_')
            ->snake()
            ->replace('_', ' ')
            ->title();

        return match ($type) {
            LayoutType::Index => "$modelName Index",
            LayoutType::Show => "$modelName Details",
            default => "$name $modelName",
        };
    }

    /**
     * @return class-string<Layout&Model>
     */
    protected static function layoutModel(): string
    {
        return config()->get('contentable.layouts.model');
    }
}
