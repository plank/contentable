<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait IsContent
{
    public function contentable(): MorphMany
    {
        return $this->morphMany(config('contentable.model'), 'contentable', 'content_type', 'content_id');
    }
}