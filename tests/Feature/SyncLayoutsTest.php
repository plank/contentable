<?php

use Plank\Contentable\Enums\LayoutType;
use Plank\Contentable\Tests\Helper\Models\Layout;
use Plank\Contentable\Tests\Helper\Models\Lesson;
use Plank\Contentable\Tests\Helper\Models\Page;
use Plank\Contentable\Tests\Helper\Models\Post;
use Plank\Contentable\Tests\Helper\Models\Product;

use function Pest\Laravel\artisan;

describe('It syncs Layouts for Blade', function () {
    beforeEach(function () {
        setBladePath('sync');
    });

    it('creates global and layoutable layouts on sync', function () {
        artisan('contentable:sync')
            ->assertExitCode(0);

        expect($layout = Layout::where('key', 'default')->first())->not->toBeNull();
        expect($layout->name)->toBe('Default');
        expect($layout->type)->toBe(LayoutType::Global);
        expect($layout->layoutable)->toBeNull();

        expect($layout = Layout::where('key', 'holidays')->first())->not->toBeNull();
        expect($layout->name)->toBe('Holidays');
        expect($layout->type)->toBe(LayoutType::Global);
        expect($layout->layoutable)->toBeNull();

        expect($layout = Layout::where('key', 'lessons.index')->first())->not->toBeNull();
        expect($layout->name)->toBe('Lesson Index');
        expect($layout->type)->toBe(LayoutType::Index);
        expect($layout->layoutable)->toBe(Lesson::layoutKey());

        expect($layout = Layout::where('key', 'lessons.show')->first())->not->toBeNull();
        expect($layout->name)->toBe('Lesson Details');
        expect($layout->type)->toBe(LayoutType::Show);
        expect($layout->layoutable)->toBe(Lesson::layoutKey());

        expect($layout = Layout::where('key', 'pages.index')->first())->not->toBeNull();
        expect($layout->name)->toBe('Page Index');
        expect($layout->type)->toBe(LayoutType::Index);
        expect($layout->layoutable)->toBe(Page::layoutKey());

        expect($layout = Layout::where('key', 'pages.show')->first())->not->toBeNull();
        expect($layout->name)->toBe('Page Details');
        expect($layout->type)->toBe(LayoutType::Show);
        expect($layout->layoutable)->toBe(Page::layoutKey());

        expect($layout = Layout::where('key', 'pages.landing')->first())->not->toBeNull();
        expect($layout->name)->toBe('Landing Page');
        expect($layout->type)->toBe(LayoutType::Custom);
        expect($layout->layoutable)->toBe(Page::layoutKey());

        expect($layout = Layout::where('key', 'posts.index')->first())->not->toBeNull();
        expect($layout->name)->toBe('Post Index');
        expect($layout->type)->toBe(LayoutType::Index);
        expect($layout->layoutable)->toBe(Post::layoutKey());

        expect($layout = Layout::where('key', 'posts.show')->first())->not->toBeNull();
        expect($layout->name)->toBe('Post Details');
        expect($layout->type)->toBe(LayoutType::Show);
        expect($layout->layoutable)->toBe(Post::layoutKey());

        expect($layout = Layout::where('key', 'posts.featured')->first())->not->toBeNull();
        expect($layout->name)->toBe('Featured Post');
        expect($layout->type)->toBe(LayoutType::Custom);
        expect($layout->layoutable)->toBe(Post::layoutKey());

        expect($layout = Layout::where('key', 'products.index')->first())->not->toBeNull();
        expect($layout->name)->toBe('Product Index');
        expect($layout->type)->toBe(LayoutType::Index);
        expect($layout->layoutable)->toBe(Product::layoutKey());

        expect($layout = Layout::where('key', 'products.show')->first())->not->toBeNull();
        expect($layout->name)->toBe('Product Details');
        expect($layout->type)->toBe(LayoutType::Show);
        expect($layout->layoutable)->toBe(Product::layoutKey());

        expect($layout = Layout::where('key', 'products.promo')->first())->not->toBeNull();
        expect($layout->name)->toBe('Promo Product');
        expect($layout->type)->toBe(LayoutType::Custom);
        expect($layout->layoutable)->toBe(Product::layoutKey());
    });

    it('can exclude Layouts from being created during sync', function () {
        config()->set('contentable.layouts.sync.excluded', [
            'products.*',
            'pages.landing',
            'holidays',
        ]);

        artisan('contentable:sync')
            ->assertExitCode(0);

        expect(Layout::count())->toBe(8);
        expect(Layout::where('key', 'products.index')->exists())->toBeFalse();
        expect(Layout::where('key', 'products.show')->exists())->toBeFalse();
        expect(Layout::where('key', 'products.promo')->exists())->toBeFalse();
        expect(Layout::where('key', 'pages.landing')->exists())->toBeFalse();
        expect(Layout::where('key', 'holidays')->exists())->toBeFalse();
    });

    it('does not duplicate global and layoutable layouts when sync is re-run', function () {
        artisan('contentable:sync')
            ->assertExitCode(0);

        expect(Layout::count())->toBe(13);

        artisan('contentable:sync')
            ->assertExitCode(0);

        expect(Layout::count())->toBe(13);
    });

    it('does not delete missing global layouts on sync', function () {
        Layout::factory()->create([
            'key' => 'custom.global',
            'name' => 'Custom Global',
            'type' => LayoutType::Custom,
        ]);

        artisan('contentable:sync')
            ->assertExitCode(0);

        expect(Layout::where('key', 'custom.global')->exists())->toBeTrue();
    });

    it('does not delete missing layoutable layouts on sync', function () {
        Layout::factory()->create([
            'key' => 'pages.y2k',
            'name' => 'Y2K Page',
            'type' => LayoutType::Custom,
            'layoutable' => Page::layoutKey(),
        ]);

        artisan('contentable:sync')
            ->assertExitCode(0);

        expect(Layout::where('key', 'pages.y2k')->exists())->toBeTrue();
    });
});
