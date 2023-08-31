<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface RenderableInterface
{

    public function renderHtml(): string;

    public function renderJson(): string;

    public function renderableFields(): array;

    public function renderable(): MorphMany;
}