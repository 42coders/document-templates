<?php

namespace BWF\DocumentTemplates\Layouts;

use BWF\EditableTemplates\EditableTemplateInterface;

interface LayoutInterface
{
    /**
     * @return EditableTemplateInterface[]
     */
    public function getTemplates();

    public function load($template);
}