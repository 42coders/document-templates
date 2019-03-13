<?php

namespace BWF\DocumentTemplates\Layouts;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\EditableTemplates\EditableTemplateInterface;
use BWF\TemplateDataSources\TemplateDataSourceInterface;

interface LayoutInterface
{
    /**
     * @return EditableTemplateInterface[]
     */
    public function getTemplates();

    /**
     * @param string $template
     * @return mixed
     */
    public function load($template);

    /**
     * @param EditableTemplate[] $templates
     * @param TemplateDataSourceInterface[] $data
     * @return string
     */
    public function render($templates, $data);
}