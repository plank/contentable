<?php

namespace Plank\Contentable\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait IsContent
{
    public function contentable(): MorphMany
    {
        return $this->morphMany(config('contentable.model'), 'contentable');
    }
}