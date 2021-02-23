<?php


namespace BWF\DocumentTemplates\DocumentTemplates;

use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\Renderers\TwigRenderer;

trait DocumentTemplate
{
    use BaseDocumentTemplate;

    /**
     * @var \BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface
     */
    protected $model = null;

    /**
     * @param \BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface|null $model
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function init(DocumentTemplateModelInterface $model = null)
    {
        $this->model = $model;

        if ($model === null) {
            $modelClass = config('document_templates.model_class', DocumentTemplateModel::class);
            $this->model = $modelClass::byDocumentClass(get_class($this))->first();
        }

        $this->renderer = new TwigRenderer();
        $this->layout = new TwigLayout();

        if ($this->model) {
            $layoutName = $this->model->getLayoutName();

            if ($layoutName) {
                $this->layout->load($layoutName);
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
        $layoutTemplateNames = $layoutTemplates->map(function ($item) {
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
