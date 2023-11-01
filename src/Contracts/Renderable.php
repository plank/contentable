<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Renderable
{

    public function renderHtml(): string;

    public function renderJson(): string;

    public function renderableFields(): array;

    public function content(): MorphMany;

    public function contentable(): ?Contentable;
}