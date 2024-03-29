<?php

namespace Plank\Contentable\Tests\Helper\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Plank\Contentable\Tests\Helper\Models\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'title' => $title = $this->faker->sentence,
            'slug' => str($title)->slug(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Post $post) {
            $post->slug = (string) str($post->title)->slug();
        });
    }
}
