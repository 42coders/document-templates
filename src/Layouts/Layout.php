<?php

namespace BWF\DocumentTemplates\Layouts;

abstract class Layout implements LayoutInterface
{
    public function getTemplates()
    {
        // TODO: Implement getTemplates() method.
    }

    public function load($template)
    {
        // TODO: Implement load() method.
    }

    /**
     * @param \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[] $templates
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface[] $dataSources
     * @return string
     */
    public function render($templates, $dataSources)
    {
        return '';
    }
}