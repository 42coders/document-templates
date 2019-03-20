<?php

namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\Renderers\TwigRenderer;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;
use BWF\DocumentTemplates\Tests\TestCase;

class DocumentTemplateTest extends TestCase
{
    use IterableTemplateData;

    /**
     * @var DocumentTemplate $documentTemplate
     */
    protected $documentTemplate;

    protected $expectedPlaceholders = [
        "users" => [
            0 => "user.id",
            1 => "user.name",
        ],
        "orders" => [
            0 => "order.id",
            1 => "order.description",
        ],
        0 => "test.title",
        1 => "test.name",
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->documentTemplate = new DemoDocumentTemplate();

        $layout = new TwigLayout();
        $layout->load(__DIR__ . '/../Stubs/TestIterableDataSource.html.twig');

        $this->documentTemplate->setLayout($layout);
        $this->documentTemplate->setRenderer(new TwigRenderer($layout));
    }

    public function testGetTemplatePlaceholders()
    {
        $placeholders = $this->documentTemplate->getTemplatePlaceholders();
        $this->assertEquals($this->expectedPlaceholders, $placeholders);
    }

    public function testRender()
    {
        $this->documentTemplate->addTemplateData($this->getTestUsers(), 'users');
        $this->documentTemplate->addTemplateData($this->getTestOrders(), 'orders');

        $output = $this->documentTemplate->render();
        $expectedOutput = file_get_contents(__DIR__ . '/../Stubs/TestIterableDataSource.expected.html');

        $this->assertEquals($expectedOutput, $output);
    }

    public function testStore()
    {
        $documentTemplateData = $this->documentTemplate->toArray();

        $expectedData = [
            'name' => '',
            'document' => DemoDocumentTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ];

        $documentTemplateModel = new DemoDocumentTemplateModel();
        $documentTemplateModel->fill($documentTemplateData);
        $documentTemplateModel->save();

        $this->assertDatabaseHas('document_templates', $expectedData);
    }

}
