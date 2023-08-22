<?php

namespace Plank\Contentable;

use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Contracts\ContentInterface;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Plank\Contentable\Commands\ContentableCommand;

class ContentableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('contentable')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_contents_table')
            ->hasCommand(ContentableCommand::class);
    }

    public function packageBooted()
    {

        \Plank\Contentable\Facades\Contentable::discover();
        $contents = config('contentable.contents');
        $contentables = config('contentable.contentables');

        // create the relationships on the fly / at runtime
        // https://laravel.com/docs/10.x/eloquent-relationships#dynamic-relationships
        foreach ($contents as $content) {
            foreach ($contentables as $contentable) {
                $relationContent = str($content)->afterLast("\\")->lower()->plural();
                $relationContentable = str($contentable)->afterLast("\\")->lower()->plural();
                $content::resolveRelationUsing('contentable', function ($content) use ($contentable) {
                    return $content->morphedByMany($contentable, 'contentable', 'contentables', 'content_id');
                });
                $contentable::resolveRelationUsing('content', function ($contentable) use ($content) {
                   return $contentable->morphedByMany($content, 'content', 'contentables', 'contentable_id');
                });
            }
        }

    }
}
