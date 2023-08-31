<?php

namespace Plank\Contentable\Concerns;


use Illuminate\Database\Eloquent\Relations\MorphMany;
use Plank\Contentable\Models\Content;

trait CanRender
{
    public function renderable(): MorphMany
    {
        return $this->morphMany(Content::class, 'renderable');
    }

    /**
     * Encodes the renderable fields as JSON to be passed to the front-end.
     *
     * @return string
     */
    public function renderJson($fields = []): string
    {
        // Given `toJson()` exists on eloquent already, do we need this?
        return json_encode($this->only($fields ?: $this->renderableFields()));
    }

    /**
     * Accessor for fields that are set to be rendered when serializing to
     *
     * @return string[]
     */
    public function renderableFields(): array
    {
        $defaults = ['title', 'content'];
        return property_exists($this, 'renderableFields') ? $this->renderableFields : $defaults;
    }
}