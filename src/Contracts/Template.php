<?php

namespace Plank\Contentable\Contracts;

interface Template
{
    public function layout(): Layout;

    public function layoutable(): Layoutable;
}
