<?php


namespace BWF\DocumentTemplates\Http\Controllers;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateFactory;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplate;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DocumentTemplatesController extends Controller
{
    use ManagesDocumentTemplates;

    use IterableTemplateData;

    protected $documentClasses = [];

    protected function createDocumentTemplateModelFromRequest(Request $request)
    {
        $name = $request->name;
        $layout = $request->layout;
        $documentClass = $request->document_class;

        $availableClasses = $this->getAvailableClasses();
        $availableLayouts = $this->getAvailableLayouts();

        $documentTemplateModel = new DocumentTemplateModel();
        $documentTemplateModel->fill([
            'name' => $name,
            'document_class' => $availableClasses[$documentClass],
            'layout' => $availableLayouts[$layout]
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $documentTemplate = $this->createDocumentTemplateModelFromRequest($request);

        if ($documentTemplate->save()) {
            return redirect(route('document-templates.edit', [$documentTemplate]));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $documentTemplate = DocumentTemplateModel::findOrFail($id);

        $layouts = $this->getAvailableLayouts();
        $placeholders = $this->getPlaceholders($documentTemplate);
        $templates = $this->getTemplates($documentTemplate);

        $params = [
            'documentTemplate' => $documentTemplate,
            'layouts' => $layouts,
            'placeholders' => $placeholders,
            'templates' => $templates
        ];

        return view('document-templates::document-templates.edit', $params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $documentTemplate = DocumentTemplateModel::findOrFail($id);

        $name = $request->name;
        $layout = $request->layout;

        $availableLayouts = $this->getAvailableLayouts();

        $documentTemplate->fill([
            'name' => $name,
            'layout' => $availableLayouts[$layout]
        ]);

        $documentTemplate->save();

        foreach($request->templates as $template){
            $editableTemplate = EditableTemplate::firstOrNew(['id' => $template['id']]);
            $editableTemplate->document_template_id = $documentTemplate->id;
            $editableTemplate->fill($template);
            $editableTemplate->save();
        }

        return redirect(route('document-templates.edit', [$documentTemplate]));
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