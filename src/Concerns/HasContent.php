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
            'attached' => [], 'detached' => []
        ];

        // get intersect of input and attached

        // diff intersect from attached --> this gives detaching

        // diff intersect from input --> this gives attaching

    }

    public function detachContent(RenderableInterface|Collection|array $renderables)
    {
        $contentModel = config('contentable.model');

        if ($renderables instanceof  RenderableInterface) {
            $renderables = [$renderables->getKey() => $renderables::class];
        }

        // this can be improved to look at the context of each renderable type rather than each record, 1 by 1
        // ie: delete all moduleA whereIn [ids...], ModuleB where in [ids...]
        foreach ($renderables as $renderable) {
            $contentModel::where('contentable_id', $this->getKey())
                ->where('contentable_type', self::class)
                ->where('renderable_id', $renderable->getKey())
                ->where('renderable_type', $renderable::class)
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