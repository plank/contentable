<?php

namespace Plank\Contentable\Tests\Helper\Layouts;

use Plank\Contentable\Concerns\DefinesProps;
use Plank\Contentable\Data\AbstractLayoutData;
use Plank\Contentable\Tests\Helper\Layout\Props\AppPropData;

class AppLayoutData extends AbstractLayoutData
{
    use DefinesProps;

    public function __construct(
        public string $key,
        public AppPropData $props,
    ) {
    }
}
