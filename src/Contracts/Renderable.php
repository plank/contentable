<?php

namespace Plank\Contentable\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Renderable
{
    public function renderHtml(): string;

    public function renderJson(): string;

    public function renderableFields(): array;

    public function content(): MorphOne;

    public function contentables(): ?Contentable;
}
