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
}