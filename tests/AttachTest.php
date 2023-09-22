<?php

use Plank\Contentable\Models\Content;
use Plank\Contentable\Tests\Models\FakeModule;
use Plank\Contentable\Tests\Models\Page;

it('can attach new renderables to a piece of content', function () {
    $page = Page::factory()->create();
    $renderable = FakeModule::factory()->create();

    $page->attachContent($renderable, "Fake Identifier");

    expect($page->contents)
        ->toHaveCount(1);

    $module = $page->contents->first();
    expect($module->identifier)->toEqual("Fake Identifier");
    expect($module->renderable)->toEqual($renderable->fresh());
});


it('can sync new renderables to a piece of content', function () {
    $page = Page::factory()->create();
    $renderables = FakeModule::factory(2)->create();

    Content::create([
        'contentable_id' => $page->id,
        'contentable_type' => $page::class,
        'renderable_id' => $renderables[0]->id,
        'renderable_type' => $renderables[0]::class
    ]);

    Content::create([
        'contentable_id' => $page->id,
        'contentable_type' => $page::class,
        'renderable_id' => $renderables[1]->id,
        'renderable_type' => $renderables[1]::class
    ]);

    // add 2 new modules, keep the first module, but de-sync the second module
    // ids: 1, 3, 4
    $sync = FakeModule::factory(2)->create()->merge([$renderables[0]]);

    $page->syncContent($sync);

    $attached = $page->contents->pluck('renderable_type', 'renderable_id')->all();
    $expected = [
        1 => FakeModule::class,
        3 => FakeModule::class,
        4 => FakeModule::class
    ];

    expect($attached)->toEqual($expected);

});

