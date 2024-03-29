<?php

namespace Plank\Contentable\Tests\Helper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\CanRender;
use Plank\Contentable\Contracts\Renderable;
use Plank\Contentable\Tests\Helper\Database\Factories\FakeModuleFactory;

class FakeModule extends Model implements Renderable
{
    use CanRender;
    use HasFactory;

    protected $guarded = ['id'];

    protected $renderableFields = ['title', 'body'];

    protected static function newFactory()
    {
        return FakeModuleFactory::new();
    }

    public function renderHtml(): string
    {
        return "<div><h2>{$this->title}</h2><p>{$this->body}</p></div>";
    }
}
