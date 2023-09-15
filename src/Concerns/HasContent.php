<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;
use Plank\Contentable\Contracts\ContentInterface;
use Plank\Contentable\Contracts\RenderableInterface;

trait HasContent
{
    public function contents(): MorphMany
    {
        $contentModel = config('contentable.model');
        return $this->morphMany($contentModel, 'contentable');
    }

    public function scopeInRenderableOrder(Builder $q): void
    {
        $q->orderBy($this->renderOrderColumnField());
    }

    public function attachContent(RenderableInterface $renderable, $identifier = null)
    {
        if (Cache::has("contentable.html.{$this->getKey()}")) {
            Cache::delete("contentable.html.{$this->getKey()}");
        }

        if (Cache::has("contentable.json.{$this->getKey()}")) {
            Cache::delete("contentable.json.{$this->getKey()}");
        }

        return $this->contents()->create(array_merge([
            'renderable_type' => $renderable::class,
            'renderable_id' => $renderable->getKey()
        ], $identifier ? ['identifier' => $identifier] : []));
    }

    public function renderHtml(): string
    {
        if (Cache::has("contentable.html.{$this->getKey()}")) {
            return Cache::get("contentable.html.{$this->getKey()}");
        }

        $output = "";
        foreach ($this->contents as $content) {
            $output .= $content->renderable->renderHtml() . "\n";
        }

        Cache::put("contentable.html.{$this->getKey()}", $output, 10800);

        return $output;
    }

    public function renderJson(): string
    {
        if (Cache::has("contentable.json.{$this->getKey()}")) {
            return Cache::get("contentable.json.{$this->getKey()}");
        }

        $output = [];

        foreach ($this->contents as $content) {
            $output[] = $content->renderable->renderJson();
        }

        $output = json_encode($output);

        Cache::put("contentable.json.{$this->id}", $output, 10800);

        return $output;
    }

    public function renderOrderColumnField()
    {
        return $this->render_order_column ?? $this->getKeyName();
    }
}