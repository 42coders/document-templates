<?php

namespace BWF\DocumentTemplates\Layouts;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplateInterface;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface;

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
     * @param TemplateDataSourceInterface[] $dataSources
     * @return string
     */
    public function render($templates, $dataSources);
}