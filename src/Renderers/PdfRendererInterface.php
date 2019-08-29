<?php


namespace BWF\DocumentTemplates\Renderers;


use BWF\DocumentTemplates\Layouts\LayoutInterface;

interface PdfRendererInterface
{
    /**
     * @param \BWF\DocumentTemplates\Layouts\LayoutInterface $layout
     * @param \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[] $templates
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource[] $data
     * @param string $filePath
     * @return mixed
     */
    public function render(LayoutInterface $layout, $templates, $data, $filePath);
}