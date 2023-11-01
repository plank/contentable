<?php

use Plank\Contentable\Models\Content;
use Plank\Contentable\Tests\Helper\Models\FakeModule;
use Plank\Contentable\Tests\Helper\Models\Page;

it('can attach new renderables to a piece of content', function () {
    $page = Page::factory()->create();
    $renderable = FakeModule::factory()->create();

    $attached = $page->attachContent($renderable, "Fake Identifier")->first();

    expect($page->contents)
        ->toHaveCount(1);

    $module = $page->contents->first();
    expect($module->identifier)->toEqual("Fake Identifier");
    expect($module->renderable)->toEqual($renderable->fresh());

    expect($attached->renderable_id)->toEqual($renderable->id);
    expect($attached->renderable_type)->toEqual($renderable::class);
    expect($attached->contentable_id)->toEqual($page->id);
    expect($attached->contentable_type)->toEqual($page::class);

});


it('can sync new renderables to a piece of content', function () {
    $page = Page::factory()->create();
    $renderables = FakeModule::factory(2)->create();
    $updated_at = $page->updated_at;

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

    // simulate some time passing
    $this->travel(3)->hours();

    $page->syncContent($sync);

    $attached = $page->fresh()->contents->pluck('renderable_type', 'renderable_id')->all();
    $expected = [
        1 => FakeModule::class,
        3 => FakeModule::class,
        4 => FakeModule::class
    ];

    expect($attached)->toEqual($expected);
    expect($page->fresh()->contents->pluck('renderable_id')->all())->toEqual([1,3,4]);
    expect($page->fresh()->updated_at)->not()->toEqual($updated_at);


});

