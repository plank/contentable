<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Plank\Contentable\Contracts\Renderable;
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

    /**
     * Attach one or many pieces of content to this Contentable
     *
     * @param Renderable|Collection|array $renderable either a single instance of Renderable or a collection/array of them.
     * @param $identifier
     * @return Collection
     */
    public function attachContent(Renderable|Collection|array $renderable, $identifier = null): Collection
    {
        Contentable::clearCache($this->getKey());

        if (is_array($renderable)) {
            $renderable = collect($renderable);
        }

        if ($renderable instanceof Collection) {
            return $this->contents()->createMany($renderable->map(function(Renderable $r) {
                return $this->formatKeys($r);
            }));
        }

        $attach = array_merge($this->formatKeys($renderable), $identifier ? ['identifier' => $identifier] : []);
        return collect([$this->contents()->create($attach)]);
    }

    /**
     * Update the models attached via the contents() relation to match the passed collection of $renderables.
     *
     * @param Renderable|Collection|array $renderables either a single instance of Renderable or a collection/array of them.
     * @param bool $detaching
     * @return array[]
     */
    public function syncContent(Renderable|Collection|array $renderables, bool $detaching = true): array
    {
        $changes = [
            'attached' => [], 'detached' => []
        ];

        // get intersect of input and attached
        // Diff on collection of models only works when both collections are an Eloquent Collection.
        $renderables = EloquentCollection::wrap($renderables);
        $attached = EloquentCollection::make($this->contents->pluck('renderable'));
        $intersect = $renderables->intersect($attached);

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

    /**
     * Remove passed renderables from this contentables contents() relation.
     *
     * @param Renderable|Collection|array $renderables either a single instance of Renderable or a collection/array of them.
     * @return void
     */
    public function detachContent(Renderable|Collection|array $renderables): void
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

    private function formatKeys(Renderable|Collection|array $renderables)
    {
        if ($renderables instanceof Renderable) {
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