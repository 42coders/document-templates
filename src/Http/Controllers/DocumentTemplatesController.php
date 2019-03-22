<?php


namespace BWF\DocumentTemplates\Http\Controllers;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DocumentTemplatesController extends Controller
{
    use ManagesDocumentTemplates;

    protected $documentClasses = [
        DemoDocumentTemplate::class
    ];

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

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $layouts = $this->getAvailableLayouts();
        $classes = $this->getAvailableClasses();

        return view('document-templates::document-templates.create', [
                'layouts' => $layouts,
                'classes' => $classes
            ]
        );
    }

}