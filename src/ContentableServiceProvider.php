<?php

namespace Plank\Contentable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ContentableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('contentable')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations([
                'create_layouts_table',
                'create_templates_table',
            ]);
    }
}
