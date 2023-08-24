<?php

namespace Plank\Contentable\Concerns;

trait CanRender
{
    /**
     * Encodes the renderable fields as JSON to be passed to the front-end.
     *
     * @return string
     */
    public function asJson(): string
    {
        return json_encode($this->only($this->renderableFields()));
    }

    public function renderableFields()
    {
        $defaults = ['title', 'body'];
        return property_exists($this, 'renderableFields') ? $this->renderableFields : $defaults;
    }
}