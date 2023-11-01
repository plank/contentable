<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Contentable\Contracts\Contentable;
use Plank\Contentable\Contracts\Renderable;

trait CanRender
{
    public static function bootCanRender()
    {
        static::updated(function (Renderable $renderable) {
            $renderable->contentable()?->clearCache();

        });

        static::deleted(function (Renderable $renderable) {
            $renderable->contentable()?->clearCache();
        });

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class))) {
            static::restored(function (Renderable $renderable) {
                $renderable->contentable()?->clearCache();
            });
        }
    }

    public function content(): MorphMany
    {
        $contentModel = config('contentable.model');
        return $this->morphMany($contentModel, 'renderable');
    }

    public function contentable(): ?Contentable
    {
        return $this->content?->contentable;
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
        return property_exists($this,'renderableFields') ? $this->renderableFields : ['title', 'body'];
    }
}