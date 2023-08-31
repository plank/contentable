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
}
