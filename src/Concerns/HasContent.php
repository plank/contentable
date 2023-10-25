<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Plank\Contentable\Contracts\RenderableInterface;
use Plank\Contentable\Facades\Contentable;

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

    public function attachContent(RenderableInterface|Collection|array $renderable, $identifier = null): Collection
    {
        Contentable::clearCache($this->getKey());

        if (is_array($renderable)) {
            $renderable = collect($renderable);
        }

        if ($renderable instanceof Collection) {
            return $this->contents()->createMany($renderable->map(function(RenderableInterface $r) {
                return $this->formatKeys($r);
            }));
        }

        $attach = array_merge($this->formatKeys($renderable), $identifier ? ['identifier' => $identifier] : []);
        return collect([$this->contents()->create($attach)]);
    }

    public function syncContent($renderables, $detaching = true): array
    {
        $changes = [
            'attached' => [], 'detached' => []
        ];

        // get intersect of input and attached
        // Diff on collection of models only works when both collections are an Eloquent Collection.
        $attached = EloquentCollection::make($this->contents->pluck('renderable'));
        $intersect = EloquentCollection::wrap($renderables)->intersect($attached);

        // diff intersect from attached --> this gives detaching
        if ($detaching) {
            $detach = $attached->diff($intersect);

            if (count($detach) > 0) {
                $this->detachContent($detach);
                $changes['detached'] = $this->formatKeys($detach);
            }
        }

        // diff intersect from input --> this gives attaching
        $attach = $renderables->diff($intersect);
        if (count($attach) > 0) {
            $changes['attached'] = $this->formatKeys($this->attachContent($attach)->pluck('renderable'));
        }

        if (count($changes['attached']) + count($changes['detached']) > 0) {
            $this->touch();
        }

        return $changes;
    }

    public function detachContent(RenderableInterface|Collection|array $renderables): void
    {
        $contentModel = config('contentable.model');

        $renderables = EloquentCollection::wrap($renderables);

        $classes = [];
        foreach ($renderables as $renderable) {
            $classes[$renderable::class][] = $renderable->getKey();
        }

        foreach ($classes as $class => $ids) {
            $contentModel::where('contentable_id', $this->getKey())
                ->where('contentable_type', self::class)
                ->where('renderable_type', $class)
                ->whereIn('renderable_id', $ids)
                ->delete();
        }
    }

    private function formatKeys(RenderableInterface|Collection|array $renderables)
    {
        if ($renderables instanceof RenderableInterface) {
            return [
                'renderable_type' => $renderables::class,
                'renderable_id' => $renderables->getKey()
            ];
        }

        if (is_array($renderables)) {
            $renderables = collect($renderables);
        }

        return $renderables->map(function ($renderable) {
            return [
                'renderable_type' => $renderable::class,
                'renderable_id' => $renderable->getKey()
            ];
        })->all();
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

    public function renderOrderColumnField()
    {
        return $this->render_order_column ?? $this->getKeyName();
    }
}