<?php

namespace Plank\Contentable\Data;

use Plank\Contentable\Concerns\DefinesProps;

class LayoutData extends AbstractLayoutData
{
    use DefinesProps;

    public function __construct(
        public string $key,
        public PropData $props
    ) {
    }
}
