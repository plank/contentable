<?php

namespace Plank\Contentable\Tests\Helper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Plank\Contentable\Concerns\HasLayouts;
use Plank\Contentable\Contracts\Layoutable;

class Page extends Model implements Layoutable
{
    use HasFactory;
    use HasLayouts {
        layoutKey as traitLayoutKey;
    }

    protected $guarded = ['id'];

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

    public function discount(): int
    {
        return 10;
    }

    public function layoutKey(): string
    {
        if ($this->title === 'Promotions') {
            return 'promotion';
        }

        return $this->traitLayoutKey();
    }
}
