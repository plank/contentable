<?php

namespace Plank\Contentable\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\CanRender;
use Plank\Contentable\Contracts\RenderableInterface;
use Plank\Contentable\Tests\Factories\FakeModuleFactory;

class FakeModule extends Model implements RenderableInterface
{
    use HasFactory;
    use CanRender;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return FakeModuleFactory::new();
    }

    public function renderHtml(): string
    {
        return "<div><h2>{$this->title}</h2><p>{$this->content}</p></div>";
    }


}