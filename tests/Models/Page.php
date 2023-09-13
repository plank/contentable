<?php

namespace Plank\Contentable\Tests\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\HasContent;
use Plank\Contentable\Tests\Factories\PageFactory;

class Page extends Model
{
    use HasFactory;
    use HasContent;

    protected $guarded = ['id'];

    /**
     * Get a new factory instance for the model.
     *
     * @param  callable|array|int|null  $count
     * @param  callable|array  $state
     * @return Factory<static>
     */
    public static function factory($count = null, $state = [])
    {
        $factory = new PageFactory();

        return $factory
            ->count(is_numeric($count) ? $count : null)
            ->state(is_callable($count) || is_array($count) ? $count : $state);
    }
}