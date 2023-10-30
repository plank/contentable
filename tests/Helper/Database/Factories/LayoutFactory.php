<?php

namespace Plank\Contentable\Tests\Helper\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Plank\Contentable\Tests\Helper\Models\Layout;

class LayoutFactory extends Factory
{
    protected $model = Layout::class;

    public function definition()
    {
        return [
            'identifier' => implode('.', $this->faker->words(2)),
        ];
    }
}
