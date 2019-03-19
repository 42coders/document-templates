<?php

namespace BWF\DocumentTemplates\Renderers;

use BWF\EditableTemplates\EditableTemplateInterface;
use BWF\Layouts\LayoutInterface;
use BWF\TemplateDataSources\TemplateDataSourceInterface;

interface RendererInterface
{
    /**
     * @param LayoutInterface $layout
     */
    public function __construct($layout);

    /**
     * @param EditableTemplateInterface[] $templates
     * @param TemplateDataSourceInterface[] $data
     * @return mixed
     */
    public function render($templates, $data);
}