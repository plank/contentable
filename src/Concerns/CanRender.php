<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Contentable\Contracts\Contentable;
use Plank\Contentable\Contracts\Renderable;

trait CanRender
{
    public static function bootCanRender()
    {
        static::updated(function (Renderable $renderable) {
            $renderable->contentables()?->clearCache();
        });

        static::deleted(function (Renderable $renderable) {
            $renderable->contentables()?->clearCache();
        });

        if (in_array(SoftDeletes::class, class_uses_recursive(static::class))) {
            static::restored(function (Renderable $renderable) {
                $renderable->contentables()?->clearCache();
            });
        }
    }

    public function content(): MorphOne
    {
        $contentModel = config('contentable.model');

        return $this->morphOne($contentModel, 'renderable');
    }

    public function contentable(): ?Contentable
    {
        return $this->content?->contentable;
    }

    /**
     * Encodes the renderable fields as JSON to be passed to the front-end.
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
        return property_exists($this, 'renderableFields') ? $this->renderableFields : ['title', 'body'];
    }

    public function formatKeys(): array
    {
        return [
            'renderable_type' => static::class,
            'renderable_id' => $this->getKey(),
        ];
    }
}
