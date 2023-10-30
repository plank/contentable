<?php

namespace Plank\Contentable\Exceptions;

use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Data\PropData;

class PropDataException extends ContentableException
{
    public static function create(string $class): self
    {
        return new self('Prop data can only be built from classes which implement "'.Layoutable::class.'" or "'.Layout::class.'". Received "'.$class.'".');
    }

    public static function property(string $class): self
    {
        return new self('The $props property must be defined as a "'.PropData::class.'" type. Received "'.$class.'".');
    }
}
