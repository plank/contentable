<?php

namespace Plank\Contentable\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\HasContent;
use Plank\Contentable\Contracts\Contentable;
use Plank\Contentable\Tests\Factories\PageFactory;

class Page extends Model implements Contentable
{
    use HasFactory;
    use HasContent;

    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return PageFactory::new();
    }
}