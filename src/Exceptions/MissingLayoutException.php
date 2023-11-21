<?php

namespace Plank\Contentable\Exceptions;

use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Contracts\Layoutable;

class MissingLayoutException extends ContentableException
{
    /**
     * @param class-string<Layoutable> $layoutable
     */
    public static function show(string $layoutable, string|int|null $key): self
    {
        return new self("No Detail layout for `{$layoutable}` with key `{$key}`.");
    }

    /**
     * @param class-string<Layoutable> $layoutable
     */
    public static function index(string $layoutable): self
    {
        return new self("No Index layout defined for `{$layoutable}`.");
    }
}
