<?php


namespace BWF\DocumentTemplates\Renderers;


use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\EditableTemplates\EditableTemplateInterface;
use BWF\Layouts\LayoutInterface;
use BWF\Renderers\RendererInterface;
use BWF\TemplateDataSources\TemplateDataSourceInterface;

class TwigRenderer implements RendererInterface
{
    /**
     * @var \BWF\DocumentTemplates\Layouts\TwigLayout $layout
     */
    protected $layout;

    public function setLayout(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }

    public function render($templates, $data)
    {
        $this->layout->render($templates, $data);
    }
}