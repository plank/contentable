<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;

trait HasContent
{
    public function contents(): MorphMany
    {
        $contentModel = config('contentable.model');

        return $this->morphMany($contentModel, 'contentable');
    }

    public function renderHtml(): string
    {
        if (Cache::has("contentable.html.{$this->getKey()}")) {
            return Cache::get("contentable.html.{$this->getKey()}");
        }

        $output = '';
        foreach ($this->contents as $content) {
            $output .= $content->renderable->renderHtml() . "\n";
        }

        Cache::put("contentable.html.{$this->getKey()}", $output, config('contentable.cache.ttl'));

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

        Cache::put("contentable.json.{$this->id}", $output, config('contentable.cache.ttl'));

        return $output;
    }

    public function clearCache($key = null): void
    {
        $key = $key ?? $this->getKey();

        if (Cache::has("contentable.html.{$key}")) {
            Cache::delete("contentable.html.{$key}");
        }

        if (Cache::has("contentable.json.{$key}")) {
            Cache::delete("contentable.json.{$key}");
        }
    }
}
