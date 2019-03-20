<?php


namespace BWF\DocumentTemplates\DocumentTemplates;

use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\Renderers\TwigRenderer;

abstract class DocumentTemplate extends BaseDocumentTemplate implements DocumentTemplateInterface
{
    /**
     * @var \BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel
     */
    protected $model = null;

    /**
     * DocumentTemplate constructor.
     * @param \BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface $model
     */
    public function __construct(DocumentTemplateModelInterface $model = null)
    {
        $this->model = $model;
        $this->renderer = new TwigRenderer();
        $this->layout = new TwigLayout();

        if ($this->model) {
            $layoutPath = config('bwf.layout_path');

            $layoutName = $this->model->getLayoutName();

            if ($layoutName) {
                $this->layout->load($layoutPath . $layoutName);
            }
        }
    }

    /**
     * @return \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[]
     */
    public function getTemplates()
    {
        $layoutTemplates = parent::getTemplates();

        if ($this->model) {
            $templates = $this->model->getEditableTemplates();
            /** @var EditableTemplate $layoutTemplate */
            foreach ($layoutTemplates as $layoutTemplate) {
                $templateName = $layoutTemplate->getName();
                if (!$templates->contains('name', $templateName)) {
                    $templates->push($layoutTemplate);
                }
            }
        } else {
            $templates = $layoutTemplates;
        }

        return $templates;
    }
}