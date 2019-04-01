<?php

namespace BWF\DocumentTemplates\Tests\Controllers;

use BWF\DocumentTemplates\DocumentTemplates;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\Http\Controllers\DocumentTemplatesController;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplate;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplateModel;
use Illuminate\Http\Request;

class DocumentTemplatesControllerTest extends FeatureTestCase
{
    /**
     * @var \BWF\DocumentTemplates\Http\Controllers\DocumentTemplatesController
     */
    protected $controller;

    protected $documentTemplateData = [
        'name' => '',
        'document_class' => DemoDocumentTemplate::class,
        'layout' => 'TestIterableDataSource.html.twig'
    ];

    protected $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new DemoDocumentTemplatesController();
        $this->request = new Request();
    }


    public function testTemplatesWithModel()
    {
        $request = new Request();

        $documentTemplateModel = new DemoDocumentTemplateModel();
        $documentTemplateModel->fill($this->documentTemplateData);

        $templates = $this->controller->templates($request, $documentTemplateModel);

        $this->assertCount(2, $templates);
    }

    public function testTemplatesWithOutModel()
    {
        $this->request->layout = 'TestLayout.html.twig';
        $this->request->document_class = DemoDocumentTemplate::class;

        $templates = $this->controller->templates($this->request);

        $this->assertCount(2, $templates);
    }

    public function testPlaceholdersWithModel()
    {
        $documentTemplateModel = new DemoDocumentTemplateModel();
        $documentTemplateModel->fill($this->documentTemplateData);

        $placeholders = $this->controller->placeholders($this->request, $documentTemplateModel);

        $this->assertCount(4, $placeholders);
    }

    public function testPlaceholdersWithOutModel()
    {
        $this->request->layout = 'TestIterableDataSource.html.twig';
        $this->request->document_class = DemoDocumentTemplate::class;

        $placeholders = $this->controller->placeholders($this->request);

        $this->assertCount(4, $placeholders);
    }

    public function testGetAvailableClasses()
    {
        $class = new \ReflectionClass(DocumentTemplatesController::class);
        $getAvailableClassesMethod = $class->getMethod('getAvailableClasses');
        $getAvailableClassesMethod->setAccessible(true);
        $documentClassesMember = $class->getProperty('documentClasses');
        $documentClassesMember->setAccessible(true);

        $classes = collect([
            'class1',
            'class2',
            DemoDocumentTemplate::class
        ]);

        $documentTemplateData = [
            'name' => '',
            'document_class' => DemoDocumentTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ];

        $documentTemplateModel = new DocumentTemplateModel();
        $documentTemplateModel->fill($documentTemplateData);
        $documentTemplateModel->save();


        $documentClassesMember->setValue($this->controller, $classes);
        $availableClasses = $getAvailableClassesMethod->invoke($this->controller);

        $expectedClasses = collect([
                'class1',
                'class2',
            ]
        );

        $this->assertEquals($expectedClasses, $availableClasses);
    }

    public function testGetAvailableLayouts()
    {
        $class = new \ReflectionClass(DocumentTemplatesController::class);
        $getAvailableLayoutsMethod = $class->getMethod('getAvailableLayouts');
        $getAvailableLayoutsMethod->setAccessible(true);

        $expectedLayouts = collect([
            0 => "TestLayout.html.twig",
            1 => "TestIterableDataSource.html.twig"
        ]);

        $availableLayouts = $getAvailableLayoutsMethod->invoke($this->controller);

        $this->assertEquals($expectedLayouts, $availableLayouts);
    }

    public function testUpdate()
    {
        $documentTemplate = DocumentTemplateModel::create([
            'name' => 'Document Template',
            'layout' => 'TestIterableDataSource.html.twig',
            'document_class' => DemoDocumentTemplate::class,
        ]);

        $documentTemplate->save();

        $dataToSave = array(
            'name' => 'Document Template Test Save',
            'layout' => 'TestIterableDataSource.html.twig',
            'document_class' => DemoDocumentTemplate::class,
            'templates' =>
                array(
                    0 =>
                        array(
                            'id' => 1,
                            'document_template_id' => 1,
                            'name' => 'user_table_rows',
                            'content' => '{% for user in users %}{{user.id}}{{user.name}}{% endfor %}

{{test.name}}{{test.title}}',
                            'created_at' => '2019-03-26 15:19:39',
                            'updated_at' => '2019-03-26 15:19:39',
                        ),
                    1 =>
                        array(
                            'id' => 2,
                            'document_template_id' => 1,
                            'name' => 'order_table_rows',
                            'content' => '{% for order in orders %}{{order.id}}{{order.description}}{% endfor %}',
                            'created_at' => '2019-03-26 15:19:39',
                            'updated_at' => '2019-03-28 10:23:12',
                        ),
                ),
        );

        $result = $this->route('PUT', 'document-templates.update', ['documentTemplate' => $documentTemplate->id], $dataToSave);

        $this->assertDatabaseHas('document_templates', ['name' => 'Document Template Test Save',]);


    }
}
