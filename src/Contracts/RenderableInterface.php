<?php

namespace Plank\Contentable\Contracts;

interface RenderableInterface
{

    public function asHtml();

    public function asJson();

    public function renderableField();
}