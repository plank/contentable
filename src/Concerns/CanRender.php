<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CanRender
{
    public function renderable(): MorphMany
    {
        $contentModel = config('contentable.model');
        return $this->morphMany($contentModel, 'renderable');
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
        return $this->renderableFields ?? ['title', 'content'];
    }
}