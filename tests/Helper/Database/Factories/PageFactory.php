<?php

namespace Plank\Contentable\Tests\Helper\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Plank\Contentable\Tests\Helper\Models\Page;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition()
    {
        return [
            'title' => $title = $this->faker->sentence,
            'slug' => str($title)->slug(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Page $page) {
            $page->slug = (string) str($page->title)->slug();
        });
    }
}
