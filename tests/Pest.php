<?php

use Plank\Contentable\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function setBladePath(string $path = ''): void
{
    $dir = realpath(__DIR__)
        .DIRECTORY_SEPARATOR
        .'Helper'
        .DIRECTORY_SEPARATOR
        .'Blade'
        .DIRECTORY_SEPARATOR;

    $fixtures = str($dir)
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
    $dir = realpath(__DIR__)
        .DIRECTORY_SEPARATOR
        .'Helper'
        .DIRECTORY_SEPARATOR
        .'Inertia'
        .DIRECTORY_SEPARATOR;

    $fixtures = str($dir)
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
        osSafeUnlink($targetFolder);
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
        osSafeUnlink($target);
    }
}

function osSafeUnlink(string $path): bool
{
    if (! is_link($path)) {
        return false; // Not a symlink, handle error or do nothing
    }

    // Check if the symlink points to a directory
    if (is_dir(readlink($path))) {
        // On Windows, use rmdir for directories
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return rmdir($path);
        } else {
            // On Unix/Linux/Mac, unlink works for directory symlinks
            return unlink($path);
        }
    } else {
        // For files, just use unlink
        return unlink($path);
    }
}
