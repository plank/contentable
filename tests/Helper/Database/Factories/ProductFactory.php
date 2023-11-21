<?php

namespace Plank\Contentable\Tests\Helper\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Plank\Contentable\Tests\Helper\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'code' => $this->faker->unique()->numberBetween(10000, 99999),
            'price_in_cents' => $this->faker->numberBetween(99, 99999),
        ];
    }
}
