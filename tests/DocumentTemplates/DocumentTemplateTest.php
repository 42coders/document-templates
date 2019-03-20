<?php

namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\EditableTemplates\HtmlTemplate;
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

        config(['bwf.layout_path' => __DIR__ . '/../Stubs/']);

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

    public function testSave()
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

    public function testConstructWithModel()
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

        $this->documentTemplate = new DemoDocumentTemplate($documentTemplateModel);
        $this->assertDatabaseHas('document_templates', $expectedData);
    }

    public function testConstructWithEmptyModel()
    {
        $documentTemplateModel = new DemoDocumentTemplateModel();
        $this->documentTemplate = new DemoDocumentTemplate($documentTemplateModel);

        $this->assertInstanceOf(DemoDocumentTemplate::class, $this->documentTemplate);
    }

    public function testRenderWithModel()
    {
        $documentTemplateModel = new DemoDocumentTemplateModel();
        $documentTemplateModel->fill([
            'name' => '',
            'document' => DemoDocumentTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ]);

        $documentTemplateModel->save();
        $documentTemplate = new DemoDocumentTemplate($documentTemplateModel);
        $templates = $documentTemplate->getTemplates();

        $this->assertEquals(2, count($templates));

        foreach ($templates as $template) {
            switch($template->getName()) {
                case 'user_table_rows':
                    $template->setContent('{% for user in users %}<tr><td>{{user.id}}</td><td>{{user.name}}</td></tr>{% endfor %}' . PHP_EOL . PHP_EOL);
                    break;
                case 'order_table_rows':
                    $template->setContent('{% for order in orders %}<tr><td>{{order.id}}</td><td>{{order.description}}</td></tr>{% endfor %}' . PHP_EOL . PHP_EOL);
                    break;
            }

            $template->fill([
                'document_template_id' => $documentTemplateModel->id,
            ]);
            $template->save();
        }

        $documentTemplate->addTemplateData($this->getTestUsers(), 'users');
        $documentTemplate->addTemplateData($this->getTestOrders(), 'orders');

        $expectedOutput = file_get_contents(__DIR__ . '/../Stubs/TestIterableDataSource.expected.html');
        $output = $documentTemplate->render();

        $this->assertEquals($expectedOutput, $output);

    }

}
