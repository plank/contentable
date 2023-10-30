<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Contentable
{
    public function contents(): MorphMany;

    public function scopeInRenderableOrder(Builder $q): void;

    public function attachContent(Renderable $renderable);

    public function renderHtml(): string;

    public function renderJson(): string;
}