<?php

namespace Plank\Contentable\Tests\Helper\Layouts\Props;

use Plank\Contentable\Data\PropData;
use Plank\Contentable\Tests\Helper\Data\MenuData;
use Plank\Contentable\Tests\Helper\Models\Layout;

class AppPropData extends PropData
{
    public MenuData $headerNav;

    public MenuData $footerNav;

    public function __construct(
        protected array $props = [],
    ) {
        parent::__construct($props);

        $layout = Layout::forced('app');

        $this->headerNav = MenuData::fromMenu($layout->menu('header'));
        $this->footerNav = MenuData::fromMenu($layout->menu('footer'));
    }
}
