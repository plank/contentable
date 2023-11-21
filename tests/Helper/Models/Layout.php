<?php

namespace Plank\Contentable\Tests\Helper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Plank\Contentable\Enums\LayoutType;
use Plank\Contentable\Models\Layout as PackageLayout;

class Layout extends PackageLayout
{
    use HasFactory;

    protected $table = 'layouts';

    protected $guarded = ['id'];

    protected $casts = [
        'type' => LayoutType::class,
        'meta' => 'json',
    ];
}
