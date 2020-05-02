<?php

namespace BWF\DocumentTemplates\Tests\Controllers\Feature;

use BWF\DocumentTemplates\DocumentTemplates;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\Tests\Controllers\DemoDocumentTemplatesController;
use BWF\DocumentTemplates\Tests\Controllers\FeatureTestCase;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplate;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestResult;

class DocumentTemplatesControllerFeatureTest extends FeatureTestCase
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

    protected $baseUrl = '';

    protected function setUp(): void
    {
        parent::setUp();

        if($this->baseUrl){
            config(['document_templates.base_url' => $this->baseUrl]);
            DocumentTemplates::routes(DemoDocumentTemplatesController::class);
        }

        $this->controller = new DemoDocumentTemplatesController();
        $this->request = new Request();
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

        $this->route('PUT', $this->baseUrl . '.update', ['document_template' => $documentTemplate->id], $dataToSave);

        $this->assertResponseOk();
        $this->assertDatabaseHas('document_templates', ['name' => 'Document Template Test Save',]);
    }

    public function testEdit()
    {
        $documentTemplate = DocumentTemplateModel::create([
            'name' => 'Document Template',
            'layout' => 'TestIterableDataSource.html.twig',
            'document_class' => DemoDocumentTemplate::class,
        ]);

        $documentTemplate->save();
        $this->get(route($this->baseUrl . '.edit', $documentTemplate->id ));

        $this->assertResponseOk();
    }

    public function testCreate()
    {
        $this->get(route($this->baseUrl .  '.create' ));
        $this->assertResponseOk();
    }

    public function testShow()
    {
        $documentTemplate = DocumentTemplateModel::create([
            'name' => 'Document Template',
            'layout' => 'TestIterableDataSource.html.twig',
            'document_class' => DemoDocumentTemplate::class,
        ]);

        $documentTemplate->save();

        $this->get(route($this->baseUrl .  '.show', $documentTemplate->id ));
        $this->assertResponseOk();
    }

    public function testIndex()
    {
        $this->get(route($this->baseUrl . '.index' ));
        $this->assertResponseOk();
    }

    public function testStore()
    {
        $dataToSave = array(
            'name' => 'Document Template Test Store',
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

        $this->route('POST', $this->baseUrl . '.store', [], $dataToSave);

        $this->assertResponseOk();
        $this->assertDatabaseHas('document_templates', ['name' => 'Document Template Test Store',]);
    }

    public function testTemplates(){
        $params = [
            "layout" => "TestLayout.html.twig",
            "document_class" => "BWF\\DocumentTemplates\\Tests\\DocumentTemplates\\DemoDocumentTemplateModel"
            ];

        DocumentTemplateModel::create([
            'name' => 'test document template',
            "layout" => "TestLayout.html.twig",
            "document_class" => "BWF\\DocumentTemplates\\Tests\\DocumentTemplates\\DemoDocumentTemplate"
        ]);

        $this->route('POST', $this->baseUrl . '.templates', ['document_template' => 1], $params);
        $this->assertResponseOk();
    }

    public function testPlaceholders(){
        $params = [
            "layout" => "TestLayout.html.twig",
            "document_class" => "BWF\\DocumentTemplates\\Tests\\DocumentTemplates\\DemoDocumentTemplateModel"
        ];

        DocumentTemplateModel::create([
            'name' => 'test document template',
            "layout" => "TestLayout.html.twig",
            "document_class" => "BWF\\DocumentTemplates\\Tests\\DocumentTemplates\\DemoDocumentTemplate"
        ]);

        $this->route('POST', $this->baseUrl . '.placeholders', ['document_template' => 1], $params);
        $this->assertResponseOk();
    }

    public function run(TestResult $result = null): TestResult
    {
        $this->baseUrl = 'documents';
        $result = parent::run($result);
        $this->baseUrl = 'newsletters';
        $result = parent::run($result);

        return $result;
    }
}
