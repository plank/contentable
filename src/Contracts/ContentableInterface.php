<?php

namespace Plank\Contentable\Contracts;

interface ContentableInterface
{
    public function attachContent(ContentInterface $content);
}