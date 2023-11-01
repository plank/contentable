<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Content extends Model
{
    protected $fillable = [
        'contentable_id',
        'contentable_type',
        'renderable_id',
        'renderable_type',
        'identifier',
        'order',
    ];

    public function renderable()
    {
        return $this->morphTo();
    }

    public function contentable()
    {
        return $this->morphTo();
    }

    public function scopeInRenderableOrder(Builder $query): void
    {
        $query->orderBy($this->renderOrderColumnField());
    }

    public function renderOrderColumnField(): string
    {
        return $this->render_order_column ?? $this->getKeyName();
    }
}