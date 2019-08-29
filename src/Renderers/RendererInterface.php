<?php

namespace BWF\DocumentTemplates\Renderers;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplateInterface;
use BWF\DocumentTemplates\Layouts\LayoutInterface;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface;

interface RendererInterface
{
    /**
     * @param \BWF\DocumentTemplates\Layouts\LayoutInterface $layout
     * @param \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[] $templates
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource[] $data
     * @return mixed
     */
    public function render(LayoutInterface $layout, $templates, $data);
}