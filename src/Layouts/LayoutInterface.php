<?php

namespace BWF\DocumentTemplates\Layouts;

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
     * @return string[]
     */
    public function getAvailableLayouts();

    /**
     * @param \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[] $templates
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface[] $dataSources
     * @return string
     */
    public function render($templates, $dataSources);

    /**
     * @param \BWF\DocumentTemplates\EditableTemplates\EditableTemplateInterface $template
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface[] $dataSources
     * @return string
     */
    public function renderSingle(EditableTemplateInterface $template, $dataSources);

    /**
     * @param EditableTemplate[] $templates
     * @param TemplateDataSourceInterface[] $dataSources
     * @return mixed
     */
    public function dump($templates, $dataSources);
}
