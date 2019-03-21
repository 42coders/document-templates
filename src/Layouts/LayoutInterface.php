<?php

namespace BWF\DocumentTemplates\Layouts;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplateInterface;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface;
use Illuminate\Support\Collection;

interface LayoutInterface
{
    /**
     * @return Collection|EditableTemplateInterface[]
     */
    public function getTemplates();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $template
     * @return mixed
     */
    public function load($template);

    /**
     * @param EditableTemplate[] $templates
     * @param TemplateDataSourceInterface[] $dataSources
     * @return string
     */
    public function render($templates, $dataSources);
}