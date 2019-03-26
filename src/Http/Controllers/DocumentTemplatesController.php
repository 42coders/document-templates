<?php


namespace BWF\DocumentTemplates\Http\Controllers;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateFactory;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DocumentTemplatesController extends Controller
{
    use ManagesDocumentTemplates;

    protected $documentClasses = [];

    protected function createDocumentTemplateModelFromRequest(Request $request, $documentTemplate = null)
    {
        $name = $request->name;
        $layout = $request->layout;
        $documentClass = $request->document_class;


        $availableClasses = $this->getAvailableClasses();
        $availableLayouts = $this->getAvailableLayouts();

        if($documentTemplate === null){
            $documentTemplate = new DocumentTemplateModel();
        }

        if($name){
            $documentTemplate->setAttribute('name', $name);
        }

        if($layout && $availableLayouts->contains($layout)){
            $documentTemplate->setAttribute('layout', $layout);
        }

        if($documentClass && collect($this->documentClasses)->contains($documentClass)){
            $documentTemplate->setAttribute('document_class', $documentClass);
        }


        return $documentTemplate;
    }

    public function templates(Request $request, DocumentTemplateModelInterface $documentTemplate = null)
    {
        $documentTemplate = $this->createDocumentTemplateModelFromRequest($request, $documentTemplate);

        return $this->getTemplates($documentTemplate);
    }

    public function placeholders(Request $request, DocumentTemplateModelInterface $documentTemplate = null)
    {
        $documentTemplate = $this->createDocumentTemplateModelFromRequest($request, $documentTemplate);

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
        $documentTemplate->save();

        return $this->update($request, $documentTemplate);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  DocumentTemplateModelInterface $documentTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentTemplateModel $documentTemplate)
    {
        $layouts = $this->getAvailableLayouts();
        $documentClasses = collect($this->documentClasses);
        $placeholders = $this->getPlaceholders($documentTemplate);
        $templates = $this->getTemplates($documentTemplate);

        $params = [
            'documentClasses' => $documentClasses,
            'documentTemplate' => $documentTemplate,
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
     * @param  DocumentTemplateModelInterface $documentTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentTemplateModel $documentTemplate)
    {
        $documentTemplate = $this->createDocumentTemplateModelFromRequest($request, $documentTemplate);

        $status = $documentTemplate->save();

        $savedTemplates = [];

        foreach($request->templates as $template){
            $editableTemplate = EditableTemplate::firstOrNew(['id' => ($template['id'] ?? null) ]);
            $editableTemplate->document_template_id = $documentTemplate->id;
            $editableTemplate->fill($template);
            $editableTemplate->save();
            $savedTemplates[] = $editableTemplate;
        }

        $result = [
            'status' => $status,
            'documentTemplate' => $documentTemplate,
            'templates' => collect($savedTemplates),
        ];

        return $result;
    }

    public function show(Request $request, $id){

        $documentTemplateModel = DocumentTemplateModel::findOrFail($id);
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