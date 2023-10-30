<?php

namespace Plank\Contentable\Tests\Helper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\HasLayouts;
use Plank\Contentable\Contracts\Layoutable;

class Post extends Model implements Layoutable
{
    use HasFactory;
    use HasLayouts;

    protected $guarded = ['id'];
}
