<?php


namespace BWF\DocumentTemplates\Http\Controllers;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateFactory;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use Illuminate\Support\Collection;

trait ManagesDocumentTemplates
{
    /**
     * @return Collection|string[]
     */
    protected function getAvailableLayouts()
    {
        $layout = new TwigLayout();

        return $layout->getAvailableLayouts();
    }

    /**
     * @return \Illuminate\Support\Collection|string[]
     */
    protected function getAvailableClasses()
    {
        $classes = collect($this->documentClasses);
        $modelClass = config('document_templates.model_class', DocumentTemplateModel::class);
        $savedClasses = $modelClass::all()->pluck('document_class');

        return $classes->diff($savedClasses);
    }


    /**
     * @param DocumentTemplateModelInterface $model
     * @return \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[]|\Illuminate\Support\Collection
     * @throws \Exception
     */
    protected function getTemplates(DocumentTemplateModelInterface $model)
    {
        $documentTemplate = DocumentTemplateFactory::build($model);

        return $documentTemplate->getTemplates();
    }

    /**
     * @param DocumentTemplateModelInterface $model
     * @return string[]
     * @throws \Exception
     */
    protected function getPlaceholders(DocumentTemplateModelInterface $model)
    {
        $documentTemplate = DocumentTemplateFactory::build($model);

        return $documentTemplate->getTemplatePlaceholders();
    }
}
