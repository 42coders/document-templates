<?php


namespace BWF\DocumentTemplates\DocumentTemplates;

use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\Renderers\TwigRenderer;

trait DocumentTemplate
{
    use BaseDocumentTemplate;

    /**
     * @var \BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel
     */
    protected $model = null;

    /**
     * DocumentTemplate initialisation.
     */
    public function init()
    {
        $this->model = DocumentTemplateModel::byDocumentClass(get_class($this))->first();

        $this->renderer = new TwigRenderer();
        $this->layout = new TwigLayout();

        $layoutPath = config('bwf.layout_path');

        if ($this->model) {
            $layoutName = $this->model->getLayoutName();

            if ($layoutName) {
                $this->layout->load($layoutPath . $layoutName);
            }
        }
    }

    /**
     * @return \Illuminate\Support\Collection|\BWF\DocumentTemplates\EditableTemplates\EditableTemplate[]
     */
    public function getTemplates()
    {
        $templates = collect();
        $layoutTemplates = $this->layout->getTemplates();
        $layoutTemplateNames = $layoutTemplates->map(function($item){
            return $item->getName();
        });

        if ($this->model) {
            $templates = $this->model->getEditableTemplates($layoutTemplateNames);
        }

        /** @var EditableTemplate $layoutTemplate */
        foreach ($layoutTemplates as $layoutTemplate) {
            $templateName = $layoutTemplate->getName();
            if (!$templates->contains('name', $templateName)) {
                $templates->push($layoutTemplate);
            }
        }

        return $templates;
    }
}