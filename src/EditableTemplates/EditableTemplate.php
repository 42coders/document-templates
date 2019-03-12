<?php

namespace BWF\DocumentTemplates\EditableTemplates;

abstract class EditableTemplate implements EditableTemplateInterface
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}