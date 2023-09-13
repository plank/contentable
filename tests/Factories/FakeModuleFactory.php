<?php

namespace Plank\Contentable\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Plank\Contentable\Tests\Models\FakeModule;

class FakeModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->sentence(10),
        ];
    }

    public function modelName()
    {
        return FakeModule::class;
    }
}