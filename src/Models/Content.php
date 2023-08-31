<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Content extends Model
{
    protected $fillable = [
        'contentable_id',
        'contentable_type',
        'renderable_id',
        'renderable_type',
        'identifier'
    ];

    public function renderable()
    {
        return $this->morphTo();
    }

    public function contentable()
    {
        return $this->morphTo();
    }
}