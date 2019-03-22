<?php


namespace BWF\DocumentTemplates\Http\Controllers;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DocumentTemplatesController extends Controller
{
    use ManagesDocumentTemplates;

    protected $documentClasses = [];

    protected function createDocumentTemplateModelFromRequest(Request $request)
    {
        $layout = $request->layout;
        $documentClass = $request->document_class;

        $documentTemplateModel = new DocumentTemplateModel();
        $documentTemplateModel->fill([
            'name' => '',
            'document_class' => $documentClass,
            'layout' => $layout
        ]);

        return $documentTemplateModel;
    }

    public function templates(Request $request, DocumentTemplateModelInterface $documentTemplate = null)
    {
        if ($documentTemplate === null) {
            $documentTemplate = $this->createDocumentTemplateModelFromRequest($request);
        }

        return $this->getTemplates($documentTemplate);
    }

    public function placeholders(Request $request, DocumentTemplateModelInterface $documentTemplate = null)
    {
        if ($documentTemplate === null) {
            $documentTemplate = $this->createDocumentTemplateModelFromRequest($request);
        }

        return $this->getPlaceholders($documentTemplate);
    }

}