<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $render_order_column
 */
interface Contentable
{
    public function contents(): MorphMany;

    public function renderHtml(): string;

    public function renderJson(): string;

    public function clearCache(): void;
}
