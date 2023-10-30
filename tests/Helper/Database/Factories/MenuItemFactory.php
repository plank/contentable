<?php

namespace Plank\Contentable\Tests\Helper\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Plank\Contentable\Tests\Helper\Models\MenuItem;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'url' => $this->faker->url,
        ];
    }
}
