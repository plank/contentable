<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Plank\Contentable\Contracts\RenderableInterface;
use Plank\Contentable\Facades\Contentable;

trait CanRender
{

    public static function bootCanRender()
    {
        Event::listen('updated', function (RenderableInterface $module) {
            foreach ($module->renderable as $content) {
                Contentable::clearCache($content->contentable->getKey());
            }
        });
    }

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