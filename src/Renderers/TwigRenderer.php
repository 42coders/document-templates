<?php


namespace BWF\DocumentTemplates\Renderers;

use BWF\DocumentTemplates\Layouts\LayoutInterface;

class TwigRenderer extends Renderer
{
    public function render(LayoutInterface $layout, $templates, $data)
    {
        return $layout->render($templates, $data);
    }
}
