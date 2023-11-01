<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface Content
{
    public function renderable(): MorphTo;

    public function contentable(): MorphTo;

    public function scopeInRenderableOrder(Builder $query): void;

    public function renderOrderColumnField(): string;
}
