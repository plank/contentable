<?php

namespace Plank\Contentable\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\CanRender;
use Plank\Contentable\Contracts\RenderableInterface;
use Plank\Contentable\Tests\Factories\FakeModuleFactory;

class FakeModule extends Model implements RenderableInterface
{
    use HasFactory;
    use CanRender;

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
        $factory = new FakeModuleFactory();

        return $factory
            ->count(is_numeric($count) ? $count : null)
            ->state(is_callable($count) || is_array($count) ? $count : $state);
    }

    public function renderHtml(): string
    {
        return "<div><h2>{$this->title}</h2><p>{$this->content}</p></div>";
    }


}