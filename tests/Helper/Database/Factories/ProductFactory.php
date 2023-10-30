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
            'title' => $this->faker->sentence,
            'code' => $this->faker->unique()->word,
            'manufacturer' => $this->faker->company,
        ];
    }
}
