<?php

namespace BWF\Renderers;

use BWF\EditableTemplates\EditableTemplateInterface;
use BWF\Layouts\LayoutInterface;
use BWF\TemplateDataSources\TemplateDataSourceInterface;

interface RendererInterface
{
    /**
     * @param LayoutInterface $layout
     * @return mixed
     */
    public function setLayout(LayoutInterface $layout);

    /**
     * @param EditableTemplateInterface[] $templates
     * @param TemplateDataSourceInterface[] $data
     * @return mixed
     */
    public function render($templates, $data);
}