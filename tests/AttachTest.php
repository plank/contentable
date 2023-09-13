<?php

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


