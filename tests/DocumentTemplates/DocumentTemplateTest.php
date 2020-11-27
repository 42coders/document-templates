<?php

namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\EditableTemplates\HtmlTemplate;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\Renderers\TwigRenderer;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;
use BWF\DocumentTemplates\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTemplateTest extends TestCase
{
    use IterableTemplateData;
    use RefreshDatabase;

    /**
     * @var DocumentTemplate $documentTemplate
     */
    protected $documentTemplate;

    protected $documentTemplateModel;

    protected $expectedPlaceholders = [
        [
            0 => "user.id",
            1 => "user.name",
        ],
        [
            0 => "order.id",
            1 => "order.description",
        ],
        [
            0 => "test.title",
            1 => "test.name",
        ]
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->documentTemplate = new DemoDocumentTemplate();
        $this->documentTemplate->init();
    }

    public function testGetTemplatePlaceholders()
    {
        $placeholders = $this->documentTemplate->getTemplatePlaceholders();
        foreach ($placeholders as $index => $placeholderGroup) {
            $this->assertEquals($this->expectedPlaceholders[$index], $placeholderGroup->getPlaceholders());
        }

    }

    public function testSave()
    {
        $documentTemplateData = [
            'name' => '',
            'document_class' => DemoDocumentTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ];

        $documentTemplateModel = new DemoDocumentTemplateModel();
        $documentTemplateModel->fill($documentTemplateData);
        $documentTemplateModel->save();

        $this->assertDatabaseHas('document_templates', $documentTemplateData);
    }

    public function testGetTemplates()
    {
        $layout = new TwigLayout();
        $layout->load('TestIterableDataSource.html.twig');
        $this->documentTemplate->setLayout($layout);

        $templates = $this->documentTemplate->getTemplates();

        $this->assertCount(2, $templates);
        $this->assertTrue($templates->contains(
            function (EditableTemplate $value) {
                return $value->getName() == 'user_table_rows';
            })
        );

        $this->assertTrue($templates->contains(
            function (EditableTemplate $value) {
                return $value->getName() == 'order_table_rows';
            })
        );
    }

    public function testGetTemplatesWithoutLayout()
    {
        $this->expectException(\Exception::class);
        $this->documentTemplate->getTemplates();
    }

    public function testRenderWithModel()
    {
        $this->documentTemplateModel = new DemoDocumentTemplateModel();
        $this->documentTemplateModel->fill([
            'name' => '',
            'document_class' => DemoDocumentTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ]);

        $this->documentTemplateModel->save();
        $this->documentTemplate->init();

        $templates = $this->documentTemplate->getTemplates();
        $this->assertEquals(2, count($templates));

        foreach ($templates as $template) {
            switch ($template->getName()) {
                case 'user_table_rows':
                    $template->setContent('{% for user in users %}<tr><td>{{user.id}}</td><td>{{user.name}}</td></tr>{% endfor %}' . PHP_EOL . PHP_EOL);
                    break;
                case 'order_table_rows':
                    $template->setContent('{% for order in orders %}<tr><td>{{order.id}}</td><td>{{order.description}}</td></tr>{% endfor %}' . PHP_EOL . PHP_EOL);
                    break;
            }

            $template->fill([
                'document_template_id' => $this->documentTemplateModel->id,
            ]);
            $template->save();
        }

        $this->documentTemplate->addTemplateData($this->getTestUsers(), 'users');
        $this->documentTemplate->addTemplateData($this->getTestOrders(), 'orders');

        $expectedOutput = file_get_contents(__DIR__ . '/../Stubs/TestIterableDataSource.expected.html');
        $output = $this->documentTemplate->render();

        $this->assertEquals($expectedOutput, $output);

        $layout = new TwigLayout();
        $layout->load(__DIR__ . '/../Stubs/TestIterableDataSource.html.twig');

        $this->documentTemplate->setLayout($layout);
        $this->documentTemplate->setRenderer(new TwigRenderer());
        $output = $this->documentTemplate->render();

        $this->assertEquals($expectedOutput, $output);
    }

    public function testDumpWithModel()
    {
        $this->documentTemplateModel = new DemoDocumentTemplateModel();
        $this->documentTemplateModel->fill([
            'name' => '',
            'document_class' => DemoDocumentTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ]);

        $this->documentTemplateModel->save();
        $this->documentTemplate->init();

        $templates = $this->documentTemplate->getTemplates();

        foreach ($templates as $template) {
            switch ($template->getName()) {
                case 'user_table_rows':
                    $template->setContent('{% for user in users %}<tr><td>{{user.id}}</td><td>{{user.name}}</td></tr>{% endfor %}' . PHP_EOL . PHP_EOL);
                    break;
                case 'order_table_rows':
                    $template->setContent('{% for order in orders %}<tr><td>{{order.id}}</td><td>{{order.description}}</td></tr>{% endfor %}' . PHP_EOL . PHP_EOL);
                    break;
            }

            $template->fill([
                'document_template_id' => $this->documentTemplateModel->id,
            ]);
            $template->save();
        }

        $this->documentTemplate->addTemplateData($this->getTestUsers(), 'users');
        $this->documentTemplate->addTemplateData($this->getTestOrders(), 'orders');

        $dump = $this->documentTemplate->dump();

        $this->assertCount(2, $dump['orders']);
        $this->assertSame('1', $dump['orders'][0]['id']);

        $this->assertCount(2, $dump['users']);
        $this->assertSame('1', $dump['users'][0]['id']);
    }
}
