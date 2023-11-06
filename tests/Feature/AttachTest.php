<?php

use Plank\Contentable\Models\Content;
use Plank\Contentable\Tests\Helper\Models\FakeModule;
use Plank\Contentable\Tests\Helper\Models\Page;

it('can format keys as expected for easy creation', function () {
    $renderable = FakeModule::factory()->create();

    $expected = [
        'renderable_type' => FakeModule::class,
        'renderable_id' => $renderable->id
    ];

    expect($renderable->formatKeys())->toEqual($expected);
});

it('can attach new renderables to a piece of content', function () {
    $page = Page::factory()->create();
    $renderable = FakeModule::factory()->create();

    $identifier = 'Fake Identifier';
    $attached = $page->contents()->create(array_merge($renderable->formatKeys(), ['identifier' => $identifier]))->first();

    expect($page->contents)
        ->toHaveCount(1);

    $module = $page->contents->first();
    expect($module->identifier)->toEqual($identifier);
    expect($module->renderable)->toEqual($renderable->fresh());

    expect($attached->renderable_id)->toEqual($renderable->id);
    expect($attached->renderable_type)->toEqual($renderable::class);
    expect($attached->contentable_id)->toEqual($page->id);
    expect($attached->contentable_type)->toEqual($page::class);
});

it('can have many renderables attached in one go using collection high order functions', function () {
    $page = Page::factory()->create();
   $renderables = FakeModule::factory(10)->create();

   $attached = $page->contents()->createMany($renderables->map->formatKeys());

   expect($attached->count())->toEqual(10);
});