<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $render_order_column
 */
interface Contentable
{
    public function contents(): MorphMany;

    public function scopeInRenderableOrder(Builder $q): void;

    public function attachContent(Renderable $renderable);

    public function renderHtml(): string;

    public function renderJson(): string;

    public function clearCache(): void;
}