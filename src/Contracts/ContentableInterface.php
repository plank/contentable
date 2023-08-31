<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface ContentableInterface
{
    public function contents(): MorphMany;

    public function scopeInRenderableOrder(Builder $q): void;

    public function attachContent(RenderableInterface $renderable);

    public function renderHtml(): string;

    public function renderJson(): string;
}