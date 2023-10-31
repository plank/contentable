<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Contracts\Layout as LayoutContract;

class Layout extends Model implements LayoutContract
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * The key on the model which identifies the layout
     */
    const LAYOUT_KEY = 'identifier';

    /**
     * {@inheritDoc}
     */
    public function layoutKey(): string
    {
        return $this->getAttribute(static::getLayoutKeyName());
    }

    /**
     * {@inheritDoc}
     */
    public static function getLayoutKeyName(): string
    {
        return static::LAYOUT_KEY;
    }
}
