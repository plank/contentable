<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Plank\Contentable\Contracts\Layout as LayoutContract;
use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Contracts\Template as TemplateContract;

/**
 * @property-read Layoutable|null $layoutable
 * @property-read LayoutContract|null $layout
 */
class Template extends Model implements TemplateContract
{
    protected $guarded = ['id'];

    const LAYOUT_KEY = 'identifier';

    public function layout(): LayoutContract
    {
        return $this->layoutModel;
    }

    public function layoutable(): Layoutable
    {
        return $this->layoutableModel;
    }

    public function layoutableModel(): MorphTo
    {
        return $this->morphTo('layoutable', 'layoutable_type', 'layoutable_id');
    }

    public function layoutModel(): BelongsTo
    {
        $layoutModel = config('contentable.layouts.model');

        return $this->belongsTo($layoutModel, 'layout_id');
    }
}
