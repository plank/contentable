<?php

namespace Plank\Contentable\Tests\Helper\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Plank\Contentable\Tests\Helper\Models\Menu;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
        ];
    }
}
