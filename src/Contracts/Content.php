<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read Contentable|null $contentable
 * @property-read Renderable|null $renderable
 */
interface Content
{
    public function renderable(): MorphTo;

    public function contentable(): MorphTo;
}
