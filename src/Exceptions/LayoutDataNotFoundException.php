<?php

namespace Plank\Contentable\Exceptions;

class LayoutDataNotFoundException extends ContentableException
{
    public static function create(string $key): self
    {
        return new self('A data class was requested for the layout "'.$key.'", but one could not be found.');
    }
}
