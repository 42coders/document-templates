<?php


namespace BWF\DocumentTemplates\Renderers;


use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\EditableTemplates\EditableTemplateInterface;
use BWF\Layouts\LayoutInterface;
use BWF\Renderers\RendererInterface;
use BWF\TemplateDataSources\TemplateDataSourceInterface;

class TwigRenderer extends Renderer
{
    /**
     * @var \BWF\DocumentTemplates\Layouts\TwigLayout $layout
     */
    protected $layout;

    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    public function render($templates, $data)
    {
        return $this->layout->render($templates, $data);
    }
}