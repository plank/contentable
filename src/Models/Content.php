<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Content extends Model
{
    protected $fillable = [
        'contentable_id',
        'contentable_type',
        'content_id',
        'content_type',
        'identifier'
    ];

    public function contentable(): MorphTo
    {
        return $this->morphTo('contentable', 'contentable_type', 'contentable_id');
    }

    public function content(): MorphTo
    {
        return $this->morphTo('contentable', 'content_type', 'content_id');
    }
}