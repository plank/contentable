<?php

namespace Plank\Contentable\Exceptions;

class ForcedLayoutException extends ContentableException
{
    public static function create(string $key): self
    {
        return new self('The layout "'.$key.'" has been forced exist, but could not be found.');
    }
}
