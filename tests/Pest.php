<?php

use Plank\Contentable\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function setBladePath(string $path = ''): void
{
    $fixtures = str(realpath(__DIR__).DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'Blade'.DIRECTORY_SEPARATOR)
        ->append($path)
        ->rtrim(DIRECTORY_SEPARATOR)
        ->explode(DIRECTORY_SEPARATOR);

    $folder = $fixtures->pop();
    $path = $fixtures->implode(DIRECTORY_SEPARATOR);

    config()->set('view.paths', [$path]);
    config()->set('contentable.layouts.folder', $folder);
}

function setInertiaPath(string $path = ''): void
{
    $fixtures = str(realpath(__DIR__).DIRECTORY_SEPARATOR.'Helper'.DIRECTORY_SEPARATOR.'Inertia'.DIRECTORY_SEPARATOR)
        ->append($path)
        ->rtrim(DIRECTORY_SEPARATOR)
        ->explode(DIRECTORY_SEPARATOR);

    $folder = $fixtures->pop();
    $path = $fixtures->implode(DIRECTORY_SEPARATOR);

    $targetPath = resource_path('js');
    $targetFolder = $targetPath.DIRECTORY_SEPARATOR.'Pages';

    if (! file_exists($targetPath)) {
        mkdir($targetPath, 0755, true);
    }

    if (file_exists($targetFolder)) {
        if (is_link($targetFolder)) {
            unlink($targetFolder);
        } else {
            rmdir($targetFolder);
        }
    }

    symlink(
        $path,
        $targetFolder
    );

    config()->set('contentable.layouts.folder', $folder);
}

function clearInertiaPath(): void
{
    $target = resource_path('js'.DIRECTORY_SEPARATOR.'Pages');

    if (file_exists($target)) {
        if (is_link($target)) {
            unlink($target);
        } else {
            rmdir($target);
        }
    }
}
