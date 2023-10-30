<?php

namespace Plank\Contentable\Tests\Helper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Plank\Contentable\Models\Layout as PackageLayout;

class Layout extends PackageLayout
{
    use HasFactory;

    protected $table = 'layouts';

    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'json',
    ];

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class)
            ->withPivot('key');
    }

    public function menu(string $key): ?Menu
    {
        return $this->menus()
            ->wherePivot('key', $key)
            ->first();
    }
}
