<?php

namespace Plank\Contentable\Tests\Helper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\HasContent;
use Plank\Contentable\Concerns\HasLayouts;
use Plank\Contentable\Contracts\Contentable;
use Plank\Contentable\Contracts\Layoutable;

class Post extends Model implements Contentable, Layoutable
{
    use HasContent;
    use HasFactory;
    use HasLayouts;

    protected $guarded = ['id'];

    protected static bool $globalLayouts = false;
}
