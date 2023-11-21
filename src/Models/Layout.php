<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Stringable;
use Plank\Contentable\Contracts\Layout as LayoutContract;
use Plank\Contentable\Enums\LayoutMode;
use Plank\Contentable\Enums\LayoutType;

class Layout extends Model implements LayoutContract
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'type' => LayoutType::class,
    ];

    protected $attributes = [
        'type' => LayoutType::Custom,
    ];

    public static function getLayoutKeyColumn(): string
    {
        return 'key';
    }

    public static function getNameColumn(): string
    {
        return 'name';
    }

    public static function getTypeColumn(): string
    {
        return 'type';
    }

    public static function getLayoutableColumn(): string
    {
        return 'layoutable';
    }

    public static function mode(): LayoutMode
    {
        return config()->get('contentable.layouts.mode');
    }

    public static function extension(): string
    {
        return static::mode()->value;
    }

    public static function separator(): string
    {
        return match (static::mode()) {
            LayoutMode::Blade => '.',
            default => '/',
        };
    }

    public static function folder(): string
    {
        $folder = str(config()->get('contentable.layouts.folder'));

        return match (static::mode()) {
            LayoutMode::Blade => static::bladeLayoutsFolder($folder),
            default => static::inertiaLayoutsFolder($folder),
        };
    }

    protected static function bladeLayoutsFolder(Stringable $folder): string
    {
        return str(config()->get('view.paths')[0])
            ->rtrim(DIRECTORY_SEPARATOR)
            ->append(DIRECTORY_SEPARATOR)
            ->append($folder->lower());
    }

    protected static function inertiaLayoutsFolder(Stringable $folder): string
    {
        return str(resource_path('js/Pages'))
            ->rtrim(DIRECTORY_SEPARATOR)
            ->append(DIRECTORY_SEPARATOR)
            ->append($folder->studly());
    }
}
