<?php


namespace BWF\DocumentTemplates\Renderers;


use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\EditableTemplates\EditableTemplateInterface;
use BWF\Layouts\LayoutInterface;
use BWF\Renderers\RendererInterface;
use BWF\TemplateDataSources\TemplateDataSourceInterface;

class TwigRenderer extends Renderer
{
    public function render($layout, $templates, $data)
    {
        return $layout->render($templates, $data);
    }
}