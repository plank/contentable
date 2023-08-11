<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasContent
{
    public function content(): MorphMany
    {
        return $this->morphMany(config('contentable.model'), 'contentable', 'contentable_type', 'contentable_id');
    }

    public function saveContent($content)
    {
        return $this->content()
            ->make()
            ->content()
            ->associate($content)
            ->save();
    }
}