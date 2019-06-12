<?php

namespace BWF\DocumentTemplates\Layouts;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplateInterface;

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

    public function renderSingle(EditableTemplateInterface $template, $dataSources)
    {
        return '';
    }
}