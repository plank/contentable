<?php

use Plank\Contentable\Enums\LayoutMode;
use Plank\Contentable\Enums\LayoutType;
use Plank\Contentable\Exceptions\MissingLayoutException;
use Plank\Contentable\Tests\Helper\Models\Layout;
use Plank\Contentable\Tests\Helper\Models\Page;
use Plank\Contentable\Tests\Helper\Models\Post;
use Plank\Contentable\Tests\Helper\Models\Product;

use function Pest\Laravel\artisan;

describe('It throws errors when layouts do not exist', function () {
    it('throws an error when the Detail layout doesnt exist', function () {
        Product::factory()->create()->layout();
    })->throws(MissingLayoutException::class);

    it('throws an error when the Index layout doesnt exist', function () {
        Product::indexLayout();
    })->throws(MissingLayoutException::class);
});

describe('It returns Blade Layouts for Layoutables', function () {
    beforeEach(function () {
        setBladePath('sync');
        artisan('contentable:sync')->assertExitCode(0);
    });

    it('can return a global Blade Layout', function () {
        $layout = Layout::query()
            ->where('key', 'default')
            ->first();

        $page = Page::factory()->create([
            'layout_id' => $layout->id,
        ]);

        expect($layout = $page->layout())->not->toBeNull();
        expect($layout->name)->toBe('Default');
        expect($layout->type)->toBe(LayoutType::Global);
        expect($layout->layoutable)->toBeNull();
    });

    it('can return the default layoutable for a Blade Layout', function () {
        $page = Page::factory()->create();

        expect($layout = $page->layout())->not->toBeNull();
        expect($layout->name)->toBe('Page Details');
        expect($layout->type)->toBe(LayoutType::Show);
        expect($layout->layoutable)->toBe(Page::layoutKey());
    });

    it('can return a layoutable Blade Layout', function () {
        $layout = Layout::query()
            ->where('key', 'pages.landing')
            ->first();

        $page = Page::factory()->create([
            'layout_id' => $layout->id,
        ]);

        expect($layout = $page->layout())->not->toBeNull();
        expect($layout->name)->toBe('Landing Page');
        expect($layout->type)->toBe(LayoutType::Custom);
        expect($layout->layoutable)->toBe(Page::layoutKey());
    });

    it('can return an index Blade layout', function () {
        expect($layout = Product::indexLayout())->not->toBeNull();
        expect($layout->name)->toBe('Product Index');
        expect($layout->type)->toBe(LayoutType::Index);
        expect($layout->layoutable)->toBe(Product::layoutKey());
    });
});

describe('It returns Inertia Layouts for Layoutables', function () {
    beforeEach(function () {
        config()->set('contentable.layouts.mode', LayoutMode::InertiaJsx);
        setInertiaPath('Sync');

        artisan('contentable:sync')->assertExitCode(0);
    });

    it('can return a global Inertia Layout', function () {
        $layout = Layout::query()
            ->where('key', 'Default')
            ->first();

        $page = Page::factory()->create([
            'layout_id' => $layout->id,
        ]);

        expect($layout = $page->layout())->not->toBeNull();
        expect($layout->name)->toBe('Default');
        expect($layout->type)->toBe(LayoutType::Global);
        expect($layout->layoutable)->toBeNull();
    });

    it('can return a layoutable Inertia Layout', function () {
        $layout = Layout::query()
            ->where('key', 'Pages/Landing')
            ->first();

        $page = Page::factory()->create([
            'layout_id' => $layout->id,
        ]);

        expect($layout = $page->layout())->not->toBeNull();
        expect($layout->name)->toBe('Landing Page');
        expect($layout->type)->toBe(LayoutType::Custom);
        expect($layout->layoutable)->toBe(Page::layoutKey());
    });

    it('can return an index Inertia layout', function () {
        expect($layout = Product::indexLayout())->not->toBeNull();
        expect($layout->name)->toBe('Product Index');
        expect($layout->type)->toBe(LayoutType::Index);
        expect($layout->layoutable)->toBe(Product::layoutKey());
    });
});

describe('It returns layout options for blade', function () {
    beforeEach(function () {
        setBladePath('sync');
        artisan('contentable:sync')->assertExitCode(0);
    });

    it('returns all but excluded layouts for models that include global layouts', function () {
        $page = Page::factory()->create();

        expect($layouts = $page->layouts())->toHaveCount(4);

        expect($layout = $layouts->where('key', 'holidays')->first())->toBeNull();

        expect($layout = $layouts->where('key', 'default')->first())->not->toBeNull();
        expect($layout->name)->toBe('Default');
        expect($layout->type)->toBe(LayoutType::Global);
        expect($layout->layoutable)->toBeNull();

        expect($layout = $layouts->where('key', 'pages.index')->first())->not->toBeNull();
        expect($layout->name)->toBe('Page Index');
        expect($layout->type)->toBe(LayoutType::Index);
        expect($layout->layoutable)->toBe(Page::layoutKey());

        expect($layout = $layouts->where('key', 'pages.show')->first())->not->toBeNull();
        expect($layout->name)->toBe('Page Details');
        expect($layout->type)->toBe(LayoutType::Show);
        expect($layout->layoutable)->toBe(Page::layoutKey());

        expect($layout = $layouts->where('key', 'pages.landing')->first())->not->toBeNull();
        expect($layout->name)->toBe('Landing Page');
        expect($layout->type)->toBe(LayoutType::Custom);
        expect($layout->layoutable)->toBe(Page::layoutKey());
    });

    it('excludes all global layouts for layoutables which should not use them', function () {
        $post = Post::factory()->create();

        expect($layouts = $post->layouts())->toHaveCount(3);

        expect($layout = $layouts->where('key', 'holidays')->first())->toBeNull();
        expect($layout = $layouts->where('key', 'default')->first())->toBeNull();

        expect($layout = $layouts->where('key', 'posts.index')->first())->not->toBeNull();
        expect($layout->name)->toBe('Post Index');
        expect($layout->type)->toBe(LayoutType::Index);
        expect($layout->layoutable)->toBe(Post::layoutKey());

        expect($layout = $layouts->where('key', 'posts.show')->first())->not->toBeNull();
        expect($layout->name)->toBe('Post Details');
        expect($layout->type)->toBe(LayoutType::Show);
        expect($layout->layoutable)->toBe(Post::layoutKey());

        expect($layout = $layouts->where('key', 'posts.featured')->first())->not->toBeNull();
        expect($layout->name)->toBe('Featured Post');
        expect($layout->type)->toBe(LayoutType::Custom);
        expect($layout->layoutable)->toBe(Post::layoutKey());
    });

    it('shows all layouts when global layouts are available and non are excluded', function () {
        $product = Product::factory()->create();

        expect($layouts = $product->layouts())->toHaveCount(5);

        expect($layout = $layouts->where('key', 'holidays')->first())->not->toBeNull();
        expect($layout->name)->toBe('Holidays');
        expect($layout->type)->toBe(LayoutType::Global);
        expect($layout->layoutable)->toBeNull();

        expect($layout = $layouts->where('key', 'default')->first())->not->toBeNull();
        expect($layout->name)->toBe('Default');
        expect($layout->type)->toBe(LayoutType::Global);
        expect($layout->layoutable)->toBeNull();

        expect($layout = $layouts->where('key', 'products.index')->first())->not->toBeNull();
        expect($layout->name)->toBe('Product Index');
        expect($layout->type)->toBe(LayoutType::Index);
        expect($layout->layoutable)->toBe(Product::layoutKey());

        expect($layout = $layouts->where('key', 'products.show')->first())->not->toBeNull();
        expect($layout->name)->toBe('Product Details');
        expect($layout->type)->toBe(LayoutType::Show);
        expect($layout->layoutable)->toBe(Product::layoutKey());

        expect($layout = $layouts->where('key', 'products.promo')->first())->not->toBeNull();
        expect($layout->name)->toBe('Promo Product');
        expect($layout->type)->toBe(LayoutType::Custom);
        expect($layout->layoutable)->toBe(Product::layoutKey());
    });
});
