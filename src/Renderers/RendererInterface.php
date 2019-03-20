<?php

namespace BWF\DocumentTemplates\Renderers;

use BWF\EditableTemplates\EditableTemplateInterface;
use BWF\Layouts\LayoutInterface;
use BWF\TemplateDataSources\TemplateDataSourceInterface;

interface RendererInterface
{
    /**
     * @param \BWF\DocumentTemplates\Layouts\Layout $layout
     * @param \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[] $templates
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource[] $data
     * @return mixed
     */
    public function render($layout, $templates, $data);
}