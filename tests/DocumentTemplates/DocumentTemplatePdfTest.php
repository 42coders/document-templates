<?php

namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\EditableTemplates\HtmlTemplate;
use BWF\DocumentTemplates\Exceptions\InvalidClassException;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\Renderers\TwigRenderer;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;
use BWF\DocumentTemplates\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTemplatePdfTest extends TestCase
{
    use IterableTemplateData;
    use RefreshDatabase;

    /**
     * @var DocumentTemplate $documentTemplate
     */
    protected $documentTemplate;

    /**
     * @var DocumentTemplateModel $documentTemplateModel
     */
    protected $documentTemplateModel;


    protected function setUp(): void
    {
        parent::setUp();
        $this->initDocumentTemplate();
    }

    protected function initDocumentTemplate()
    {
        $this->documentTemplate = new DemoDocumentTemplate();
        $this->documentTemplate->init();

        $this->documentTemplateModel = new DemoDocumentTemplateModel();
        $this->documentTemplateModel->fill([
            'name' => '',
            'document_class' => DemoDocumentTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ]);

        $this->documentTemplateModel->save();

        $this->documentTemplate->init($this->documentTemplateModel);

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
    }


    public function testRenderWithDomPdf()
    {
        $filePath = $this->documentTemplate->renderPdf(storage_path('app/test.pdf'));
        $this->assertFileExists($filePath);

    }

    public function testRenderWithInvalidPdfRenderer()
    {
        config(['document_templates.pdf_renderer' => TwigRenderer::class]);

        $this->expectException(InvalidClassException::class);
        $filePath = $this->documentTemplate->renderPdf(storage_path('app/test.pdf'));

    }
}
