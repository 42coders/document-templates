<?php

namespace BWF\DocumentTemplates\Layouts;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplateInterface;
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
     * @return string[]
     */
    public function getAvailableLayouts();

    /**
     * @param \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[] $templates
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface[] $dataSources
     * @return string
     */
    public function render($templates, $dataSources);
}