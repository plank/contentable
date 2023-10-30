<?php

namespace Plank\Contentable\Tests\Helper\Layouts\Props;

use Plank\Contentable\Contracts\Layoutable;
use Plank\Contentable\Tests\Helper\Models\Page;

class PromotionPropData extends AppPropData
{
    public function __construct(
        public int $percentDiscount,
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
        return new static(
            percentDiscount: $page->layout()?->meta['discount'] ?? 0,
        );
    }
}
