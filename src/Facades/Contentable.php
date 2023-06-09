<?php

namespace Plank\Contentable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Plank\Contentable\Contentable
 */
class Contentable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Plank\Contentable\Contentable::class;
    }
}
