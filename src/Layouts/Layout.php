<?php

namespace BWF\DocumentTemplates\Layouts;

abstract class Layout implements LayoutInterface
{
    protected $name;

    protected function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTemplates()
    {
        // TODO: Implement getTemplates() method.
    }

    public function load($template)
    {
        // TODO: Implement load() method.
    }
}