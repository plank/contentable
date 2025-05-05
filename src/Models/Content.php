<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Plank\Contentable\Contracts\Content as ContentInterface;

class Content extends Model implements ContentInterface
{
    protected $fillable = [
        'contentable_id',
        'contentable_type',
        'renderable_id',
        'renderable_type',
        'identifier',
        'order',
    ];

    protected static function boot()
    {
        static::saved(function (ContentInterface $content) {
            $content->contentable?->clearCache();
        });

        parent::boot();
    }

    public function renderable(): MorphTo
    {
        return $this->morphTo();
    }

    public function contentable(): MorphTo
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
