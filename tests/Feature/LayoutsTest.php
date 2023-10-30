<?php

use Plank\Contentable\Contracts\Layout as LayoutContract;
use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Data\AbstractLayoutData;
use Plank\Contentable\Data\LayoutData;
use Plank\Contentable\Data\PropData;
use Plank\Contentable\Models\Template;
use Plank\Contentable\Tests\Helper\Data\MenuData;
use Plank\Contentable\Tests\Helper\Layouts\PageLayoutData;
use Plank\Contentable\Tests\Helper\Layouts\ProductIndexLayoutData;
use Plank\Contentable\Tests\Helper\Layouts\ProductLayoutData;
use Plank\Contentable\Tests\Helper\Layouts\PromotionLayoutData;
use Plank\Contentable\Tests\Helper\Models\Layout;
use Plank\Contentable\Tests\Helper\Models\Menu;
use Plank\Contentable\Tests\Helper\Models\MenuItem;
use Plank\Contentable\Tests\Helper\Models\Page;
use Plank\Contentable\Tests\Helper\Models\Post;
use Plank\Contentable\Tests\Helper\Models\Product;

describe('it resolves data classes correctly', function () {
    it('returns empty prop data on basic layout data', function () {
        $data = new class extends AbstractLayoutData
        {
            public function __construct()
            {
                parent::__construct('basic');
            }

            public static function from(string|Layoutable|LayoutContract $model): static
            {
                return new self('basic');
            }
        };

        expect($data->key)->toBe('basic');
        expect($data->component)->toBe('Basic');
        expect($data->template)->toBe('layouts.basic');
        expect($data->props)->toBeInstanceOf(PropData::class);
        expect($data->oops)->toBeNull();
        expect($data->props->toArray())->toBe([]);
    });

    it('uses the default data class when nothing else is defined', function () {
        config(['contentable.layouts.blade_namespace' => 'app']);

        $post = Post::factory()->create();

        expect($data = $post->layoutData())->toBeInstanceOf(LayoutData::class);
        expect($data->key)->toBe('posts.show');
        expect($data->template)->toBe('app::layouts.posts.show');
        expect($data->component)->toBe('Posts/Show');
    });

    it('uses the default index data class when nothing else is defined', function () {
        expect(Post::indexLayoutData())->toBeInstanceOf(LayoutData::class);
        expect(Post::indexLayoutData()->key)->toBe('posts.index');
    });

    it('finds the fallback data class based on the model name', function () {
        $product = Product::factory()->create();

        expect($product->layoutData())->toBeInstanceOf(ProductLayoutData::class);
        expect($product->layoutData()->key)->toBe('products.show');
    });

    it('finds the fallback index data class based on the model name', function () {
        expect(Product::indexLayoutData())->toBeInstanceOf(ProductIndexLayoutData::class);
        expect(Product::indexLayoutData()->key)->toBe('products.index');
    });

    it('allows a custom index data class to be defined', function () {
        Layout::factory()->create([
            'identifier' => 'products.index',
        ]);

        expect($layout = Product::indexLayout())->toBeInstanceOf(Layout::class);
        expect($layout->layoutKey())->toBe('products.index');
        expect($data = $layout->data())->toBeInstanceOf(ProductIndexLayoutData::class);
        expect($data->toArray())->toMatchSnapshot();
    });

    it('allows you to access prop data from the layout data using magic methods', function () {
        $layout = Layout::factory()->create([
            'identifier' => 'products.index',
        ]);

        expect($data = $layout->data())->toBeInstanceOf(ProductIndexLayoutData::class);
        expect($data->perPage)->toBe(15);
    });
});

describe('it composes prop data for layouts', function () {
    beforeEach(function () {
        $app = Layout::factory()->create([
            'identifier' => 'app',
        ]);

        $header = Menu::factory()
            ->has(MenuItem::factory()->count(5), 'items')
            ->create();

        $footer = Menu::factory()
            ->has(MenuItem::factory()->count(3), 'items')
            ->create();

        $app->menus()->sync([
            $header->id => ['key' => 'header'],
            $footer->id => ['key' => 'footer'],
        ]);
    });

    it('allows layouts to be composed from other layouts', function () {
        $dashboard = Page::factory()->create([
            'title' => 'Dashboard',
            'paywall' => true,
        ]);

        $sideNav = Menu::factory()
            ->has(MenuItem::factory()->count(10), 'items')
            ->create();

        $dashboard->menus()->sync([$sideNav->id => ['key' => 'side_nav']]);

        expect($layoutData = $dashboard->layoutData())->toBeInstanceOf(PageLayoutData::class);
        expect($layoutData->paywall)->toBe(true);
        expect($layoutData->headerNav)->toBeInstanceOf(MenuData::class);
        expect($layoutData->headerNav->items)->toHaveCount(5);
        expect($layoutData->footerNav)->toBeInstanceOf(MenuData::class);
        expect($layoutData->footerNav->items)->toHaveCount(3);
        expect($layoutData->sideNav)->toBeInstanceOf(MenuData::class);
        expect($layoutData->sideNav->items)->toHaveCount(10);
    });

    it('allows you to define a custom layout data class on a per model basis', function () {
        $page = Page::factory()->create([
            'title' => 'Promotions',
        ]);

        $promotionLayout = Layout::factory()->create([
            'identifier' => 'promotion',
            'meta' => [
                'discount' => 10,
            ],
        ]);

        Template::create([
            'layoutable_id' => $page->id,
            'layoutable_type' => Page::class,
            'layout_id' => $promotionLayout->id,
        ]);

        expect($layoutData = $page->layoutData())->toBeInstanceOf(PromotionLayoutData::class);
        expect($layoutData->key)->toBe('promotion');
        expect($layoutData->percentDiscount)->toBe(10);
    });
});
