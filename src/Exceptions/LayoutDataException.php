<?php

namespace Plank\Contentable\Exceptions;

use Plank\Contentable\Contracts\Layoutable;

class LayoutDataException extends ContentableException
{
    public static function create(string $class): self
    {
        return new self('Layout data can only be built from classes which implement "'.Layoutable::class.'" or "'.Layout::class.'". Received "'.$class.'".');
    }
}
