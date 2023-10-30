<?php

namespace Plank\Contentable\Tests\Helper\Layouts\Props;

use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Tests\Helper\Data\MenuData;
use Plank\Contentable\Tests\Helper\Models\Page;

class PagePropData extends AppPropData
{
    public function __construct(
        public ?MenuData $sideNav,
        public bool $paywall,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     *
     * @param  Layoutable&Page  $page
     */
    public static function fromLayoutable(Layoutable $page): static
    {
        $nav = $page->menu('side_nav');

        return new static(
            sideNav: $nav ? MenuData::fromMenu($nav) : null,
            paywall: $page->paywall,
        );
    }
}
