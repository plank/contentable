<?php

namespace Plank\Contentable\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Plank\Contentable\Contracts\ContentInterface;
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

    public function attachContent(RenderableInterface|Collection|array $renderable, $identifier = null)
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

        return $this->contents()->create(array_merge($this->formatKeys($renderable), $identifier ? ['identifier' => $identifier] : []));
    }

    public function syncContent($renderables, $detaching = true)
    {
        $changes = [
            'attached' => [], 'detached' => [], 'updated' => []
        ];

        if (is_array($renderables)) {
            $renderables = collect($renderables);
        }

        // get all renderables attached currently
        $current = $this->contents->pluck('renderable_type', 'renderable_id');

        // Prep input renderables for merge
        // If input not a collection parse to a collection?
        $records = $renderables->mapWithKeys(function ($renderable) {
            return [$renderable->getKey() => $renderable::class];
        });

        // merge currently attached renderables with input renderables
        // might need to make sure renderables that have been updated _overwrite_ instead of becoming a sibling
        if ($detaching) {
            $detach = $current->diff($records);

            // diff away things from currently attached that are not in input array
            if (count($detach) > 0) {
                $this->detachContent($detach);
                $changes['detached'] = $detach;
            }
        }

        $changes = array_merge($changes, ['attached' => $this->formatKeys($this->attachContent($renderables))]);

        // Touch parent?

        return $changes;

    }

    public function detachContent(RenderableInterface|Collection|array $renderables)
    {
        $contentModel = config('contentable.model');
        // TODO: implement me
    }

    private function formatKeys(RenderableInterface|Collection|array $renderables)
    {
        if (is_array($renderables)) {
            $renderables = collect($renderables);
        }

        if ($renderables instanceof Collection) {
            return $renderables->map(function ($renderable) {
                return [
                    'renderable_type' => $renderable::class,
                    'renderable_id' => $renderable->getKey()
                ];
            })->all();
        }

        return [
            'renderable_type' => $renderables::class,
            'renderable_id' => $renderables->getKey()
        ];
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