<?php

namespace Plank\Contentable;

use Illuminate\Support\Facades\Cache;
use Plank\Contentable\Contracts\ContentableInterface;
use Plank\Contentable\Contracts\ContentInterface;

class Contentable
{
    /**
     * Discover all content and contentable models.
     *
     * @return void
     */
    public function discover(): void
    {
        // If the user has set the content/able models via the config, we can skip everything in this function
        if (config('contentable.contents') && config('contentable.contentables')) {
            return;
        }

        // if the config was cleared, but we still have the model lists cached grab them to save processing cycles
        if (Cache::has('contentable.content') && Cache::has('contentable.contentables')) {
            config([
                'contentable.contents' => Cache::get('contentable.contents'),
                'contentable.contentables' => Cache::get('contentable.contentables')
            ]);
        }

        // Read Models from Model name space by observing the filesystem
        $modelPath = config('contentable.model_path', 'Models');
        $path = app_path($modelPath) . '/*.php';
        $models = collect(glob($path))->map(fn ($file) => "App\\{$modelPath}\\" . basename($file, '.php'));

        // Get all models that implement ContentInterface
        $contents = $models->filter(function ($model) use ($modelPath) {
            $implements = class_implements($model);
            return isset($implements[ContentInterface::class]);
        })->values();

        // Get all models that implement ContentableInterface
        $contentables = $models->filter(function ($model) use ($modelPath) {
            $implements = class_implements($model);
            return isset($implements[ContentableInterface::class]);
        })->values();

        // Save this to cache, so we can avoid the overhead of reading the file system every time
        Cache::set('contentable.contents', $contents->toArray());
        Cache::set('contentable.contentables', $contentables->toArray());

        config([
            'contentable.contents' => $contents,
            'contentable.contentables' => $contentables
        ]);
    }
}
