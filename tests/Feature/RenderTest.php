<?php

use Illuminate\Support\Facades\Cache;
use Plank\Contentable\Tests\Helper\Models\FakeModule;
use Plank\Contentable\Tests\Helper\Models\Page;

it('can render html of an attached module', function () {
    $renderable = FakeModule::factory()->create();

    $expected = "<div><h2>{$renderable->title}</h2><p>{$renderable->body}</p></div>";

    expect($renderable->renderHtml())->toEqual($expected);

});

it('can render json of an attached module', function () {
    $renderable = FakeModule::factory()->create();

    $expected = json_encode([
        'title' => $renderable->title,
        'body' => $renderable->body,
    ]);

    expect($renderable->renderJson())->toEqual($expected);
});

it('can render a full page of html', function () {
    $renderable1 = FakeModule::factory()->create();
    $renderable2 = FakeModule::factory()->create();
    $renderable3 = FakeModule::factory()->create();

    $page = Page::factory()->create();

    $page->contents()->create($renderable1->formatKeys());
    $page->contents()->create($renderable2->formatKeys());
    $page->contents()->create($renderable3->formatKeys());

    $expected = "<div><h2>{$renderable1->title}</h2><p>{$renderable1->body}</p></div>\n";
    $expected .= "<div><h2>{$renderable2->title}</h2><p>{$renderable2->body}</p></div>\n";
    $expected .= "<div><h2>{$renderable3->title}</h2><p>{$renderable3->body}</p></div>\n";

    expect($page->renderHtml())->toEqual($expected);
    expect(Cache::has("contentable.html.{$page->id}"))->toBeTrue();
    expect(Cache::get("contentable.html.{$page->id}"))->toEqual($expected);
});

it('can render a full page of json', function () {
    $renderable1 = FakeModule::factory()->create();
    $renderable2 = FakeModule::factory()->create();
    $renderable3 = FakeModule::factory()->create();

    $page = Page::factory()->create();

    $page->contents()->create($renderable1->formatKeys());
    $page->contents()->create($renderable2->formatKeys());
    $page->contents()->create($renderable3->formatKeys());

    $expected = json_encode([
        $renderable1->renderJson(),
        $renderable2->renderJson(),
        $renderable3->renderJson(),
    ]);

    expect($page->renderJson())->toEqual($expected);
    expect(Cache::has("contentable.json.{$page->id}"))->toBeTrue();
    expect(Cache::get("contentable.json.{$page->id}"))->toEqual($expected);

});
