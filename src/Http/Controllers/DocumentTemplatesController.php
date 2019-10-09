<?php


namespace BWF\DocumentTemplates\Http\Controllers;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateFactory;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Http\Responses\DocumentTemplateResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DocumentTemplatesController extends Controller
{
    use ManagesDocumentTemplates;

    protected $documentClasses = [];

    protected function createDocumentTemplateModelFromRequest(Request $request, DocumentTemplateModelInterface $documentTemplateModel = null)
    {
        $name = $request->name;
        $layout = $request->layout;
        $documentClass = $request->document_class;

        $availableLayouts = $this->getAvailableLayouts();

        if ($documentTemplateModel === null) {
            $modelClass = config('document_templates.model_class', DocumentTemplateModel::class);
            $documentTemplateModel = new $modelClass();
        }

        if ($name) {
            $documentTemplateModel->setAttribute('name', $name);
        }

        if ($layout && $availableLayouts->contains($layout)) {
            $documentTemplateModel->setAttribute('layout', $layout);
        }

        if ($documentClass && collect($this->documentClasses)->contains($documentClass)) {
            $documentTemplateModel->setAttribute('document_class', $documentClass);
        }


        return $documentTemplateModel;
    }

    /**
     * @param Request $request
     * @param DocumentTemplateModelInterface $documentTemplateModel
     * @return DocumentTemplateResponse
     */
    protected function _save(Request $request, DocumentTemplateModelInterface $documentTemplateModel)
    {
        $documentTemplate = $this->createDocumentTemplateModelFromRequest($request, $documentTemplateModel);
        $status = $documentTemplate->save();

        $savedTemplates = [];

        foreach ($request->templates as $template) {
            $editableTemplate = EditableTemplate::firstOrNew(['id' => ($template['id'] ?? null) ]);
            $editableTemplate->document_template_id = $documentTemplate->id;
            $editableTemplate->fill($template);
            $editableTemplate->save();
            $savedTemplates[] = $editableTemplate;
        }

        $result = new DocumentTemplateResponse(
            $status,
            $documentTemplate,
            [],
            [],
            collect($savedTemplates)
        );

        return $result;
    }

    public function templates(Request $request, DocumentTemplateModelInterface $documentTemplateModel = null)
    {
        $documentTemplate = $this->createDocumentTemplateModelFromRequest($request, $documentTemplateModel);

        return $this->getTemplates($documentTemplate);
    }

    public function placeholders(Request $request, DocumentTemplateModelInterface $documentTemplateModel = null)
    {
        $documentTemplate = $this->createDocumentTemplateModelFromRequest($request, $documentTemplateModel);

        return $this->getPlaceholders($documentTemplate);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws \Exception
     * @return mixed
     */
    public function create()
    {
        $layouts = $this->getAvailableLayouts();
        $documentTemplate = new DocumentTemplateModel();
        $documentTemplate->document_class = $this->documentClasses[0];
        $documentTemplate->layout = $layouts[0];

        return $this->edit($documentTemplate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $documentTemplate = $this->createDocumentTemplateModelFromRequest($request);

        $result = $this->_save($request, $documentTemplate);
        $result->redirect = route('document-templates.edit', $documentTemplate->id);

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  DocumentTemplateModelInterface $documentTemplateModel
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentTemplateModelInterface $documentTemplateModel)
    {
        $layouts = $this->getAvailableLayouts();
        $documentClasses = collect($this->documentClasses);
        $placeholders = $this->getPlaceholders($documentTemplateModel);
        $templates = $this->getTemplates($documentTemplateModel);

        $params = [
            'documentClasses' => $documentClasses,
            'documentTemplate' => $documentTemplateModel,
            'layouts' => $layouts,
            'placeholders' => $placeholders,
            'templates' => $templates
        ];

        $params['data'] = $params;

        return view('document-templates::document-templates.edit', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  DocumentTemplateModelInterface $documentTemplateModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentTemplateModelInterface $documentTemplateModel)
    {
        $result = $this->_save($request, $documentTemplateModel);

        return response()->json($result);
    }

    public function show(Request $request, DocumentTemplateModelInterface $documentTemplateModel)
    {
        $documentTemplate = DocumentTemplateFactory::build($documentTemplateModel);

        $params = compact(
            'documentTemplate'
        );

        return view('document-templates::document-templates.show', $params);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documentTemplates = DocumentTemplateModel::all();

        $params = compact(
            'documentTemplates'
        );

        return view('document-templates::document-templates.index', $params);
    }
}
