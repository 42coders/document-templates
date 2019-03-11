<?php

namespace BWF\Layouts;

use BWF\EditableTemplates\EditableTemplateInterface;

interface LayoutInterface
{
    /**
     * @return EditableTemplateInterface[]
     */
    public function getTemplates();
}