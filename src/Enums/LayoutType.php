<?php

namespace Plank\Contentable\Enums;

use Illuminate\Support\Collection;

enum LayoutType: string
{
    case Show = 'show';
    case Index = 'index';
    case Global = 'global';
    case Custom = 'custom';

    /**
     * Get the cases as a collection of options keyed by their value.
     */
    public static function options(): Collection
    {
        return Collection::wrap(self::cases())
            ->mapWithKeys(fn (LayoutType $type) => [$type->value => $type->name]);
    }
}
