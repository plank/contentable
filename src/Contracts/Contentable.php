<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * @property string $render_order_column
 */
interface Contentable
{
    public function contents(): MorphMany;

    public function attachContent((Renderable&Model)|Collection|array $renderable);

    public function renderHtml(): string;

    public function renderJson(): string;

    public function clearCache(): void;
}
