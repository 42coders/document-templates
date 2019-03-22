<?php

namespace BWF\DocumentTemplates\Tests\Controllers;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\Http\Controllers\DocumentTemplatesController;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplate;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplateModel;
use BWF\DocumentTemplates\Tests\TestCase;
use Illuminate\Http\Request;

class DocumentTemplatesControllerTest extends TestCase
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

        $this->controller = new DocumentTemplatesController();
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
        $this->request->layout = 'TestIterableDataSource.html.twig';
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

        $expectedLayouts = [
            0 => "TestLayout.html.twig",
            1 => "TestIterableDataSource.html.twig"
        ];

        $availableLayouts = $getAvailableLayoutsMethod->invoke($this->controller);

        $this->assertEquals($expectedLayouts, $availableLayouts);
    }
}
