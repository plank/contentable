<?php

namespace Plank\Contentable\Enums;

enum LayoutMode: string
{
    case Blade = '.blade.php';
    case InertiaVue = '.vue';
    case InertiaJs = '.js';
    case InertiaTs = '.ts';
    case InertiaJsx = '.jsx';
    case InertiaTsx = '.tsx';
}
