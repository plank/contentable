<?php

namespace Plank\Contentable\Tests\Helper\Layouts;

use Plank\Contentable\Concerns\DefinesProps;
use Plank\Contentable\Data\AbstractLayoutData;
use Plank\Contentable\Tests\Helper\Layouts\Props\PromotionPropData;

class PromotionLayoutData extends AbstractLayoutData
{
    use DefinesProps;

    public function __construct(
        public string $key,
        public PromotionPropData $props,
    ) {
    }
}
