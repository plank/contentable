<?php

use Illuminate\Support\Facades\Cache;
use Plank\Contentable\Tests\Helper\Models\FakeModule;
use Plank\Contentable\Tests\Helper\Models\Page;

it('clears the cached html when its content changes', function () {
    $page = Page::factory()->create();
    $renderable1 = FakeModule::factory()->create();
    $renderable2 = FakeModule::factory()->create();

    $page->attachContent($renderable1);
    $page->renderHtml();

    expect(Cache::has("contentable.html.{$page->id}"))->toBeTrue();

    $page->attachContent($renderable2);

    expect(Cache::has("contentable.html.{$page->id}"))->toBeFalse();
});

it('clears the cached json when its content changes', function () {
    $page = Page::factory()->create();
    $renderable1 = FakeModule::factory()->create();
    $renderable2 = FakeModule::factory()->create();

    $page->attachContent($renderable1);
    $page->renderJson();

    expect(Cache::has("contentable.json.{$page->id}"))->toBeTrue();

    $page->attachContent($renderable2);

    expect(Cache::has("contentable.json.{$page->id}"))->toBeFalse();
});

it('clears cache if a renderable attached to a contentable is updated', function () {
    $page1 = Page::factory()->create();
    $page2 = Page::factory()->create();
    $renderable = FakeModule::factory()->create();
    $page1->attachContent($renderable);
    $page2->attachContent($renderable);

    $page1->renderHtml();
    $page1->renderJson();
    $page2->renderHtml();
    $page2->renderJson();

    expect(Cache::has("contentable.json.{$page1->id}"))->toBeTrue();
    expect(Cache::has("contentable.json.{$page2->id}"))->toBeTrue();
    expect(Cache::has("contentable.html.{$page1->id}"))->toBeTrue();
    expect(Cache::has("contentable.html.{$page2->id}"))->toBeTrue();

    $renderable->title = 'New Title!';
    $renderable->save();

    expect(Cache::has("contentable.json.{$page1->id}"))->toBeFalse();
    expect(Cache::has("contentable.json.{$page2->id}"))->toBeFalse();
    expect(Cache::has("contentable.html.{$page1->id}"))->toBeFalse();
    expect(Cache::has("contentable.html.{$page2->id}"))->toBeFalse();

});
