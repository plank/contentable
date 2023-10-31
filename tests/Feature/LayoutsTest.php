<?php

use Plank\Contentable\Tests\Helper\Models\Layout;
use Plank\Contentable\Tests\Helper\Models\Page;

beforeEach(function () {
    Layout::factory()->create([
        'identifier' => 'pages.show',
    ]);

    Layout::factory()->create([
        'identifier' => 'pages.index',
    ]);

    Layout::factory()->create([
        'identifier' => 'promotions',
    ]);
});

it('finds the show layout using the default key', function () {
    $page = Page::factory()->create();

    expect($layout = $page->layout())->toBeInstanceOf(Layout::class);
    expect($layout->layoutKey())->toBe('pages.show');
});

it('finds the index layout using the default key', function () {
    expect($layout = Page::indexLayout())->toBeInstanceOf(Layout::class);
    expect($layout->layoutKey())->toBe('pages.index');
});

it('finds the show layout using a custom key', function () {
    $page = Page::factory()->create([
        'title' => 'Promotions',
    ]);

    expect($layout = $page->layout())->toBeInstanceOf(Layout::class);
    expect($layout->layoutKey())->toBe('promotions');
});
