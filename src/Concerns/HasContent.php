<?php

namespace Plank\Contentable\Concerns;

use Plank\Contentable\Contracts\ContentInterface;

trait HasContent
{
    public function attachContent(ContentInterface $content)
    {
        return $this->content()->attach($content, ['contentable_type' => self::class]);
    }
}