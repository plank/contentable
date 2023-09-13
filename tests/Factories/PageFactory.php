<?php

namespace Plank\Contentable\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Plank\Contentable\Tests\Models\Page;

class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(1, 2);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
        ];
    }

    public function modelName()
    {
        return Page::class;
    }
}