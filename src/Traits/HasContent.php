<?php

namespace Plank\Contentable\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasContent
{
    public function content(): MorphMany
    {
        return $this->morphMany(config('contentable.model'), 'contentable');
    }
}