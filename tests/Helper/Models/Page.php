<?php

namespace Plank\Contentable\Tests\Helper\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\HasLayouts;
use Plank\Contentable\Contracts\Layoutable;

class Page extends Model implements Layoutable
{
    use HasFactory;

    use HasLayouts {
        layoutKey as traitLayoutKey;
    }

    protected $guarded = ['id'];

    public function layoutKey(): string
    {
        if ($this->title === 'Promotions') {
            return 'promotions';
        }

        return $this->traitLayoutKey();
    }
}
