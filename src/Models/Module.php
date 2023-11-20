<?php

namespace Plank\Contentable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\CanRender;

class Module extends Model
{
    use CanRender;
    use HasFactory;
}
