<?php

namespace Plank\Contentable\Tests\Helper\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Plank\Contentable\Tests\Helper\Models\Layout;

class LayoutFactory extends Factory
{
    protected $model = Layout::class;

    public function definition()
    {
        $meta = [];

        for ($i = $this->faker->numberBetween(1, 5); $i <= 5; $i++) {
            $meta[$this->faker->word] = $this->faker->word;
        }

        return [
            'key' => $key = implode('.', $this->faker->words(2)),
            'name' => (string) str($key)->replace('.', ' ')->title(),
            'meta' => $meta,
        ];
    }
}
