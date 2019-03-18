<?php

namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\Tests\TestCase;

class DemoTemplateTest extends TestCase
{

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
        ]
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->documentTemplate = new DemoTemplate();

        $layout = new TwigLayout();
        $layout->load(__DIR__ . '/../Stubs/TestIterableDataSource.html.twig');

        $this->documentTemplate->setLayout($layout);
    }

//    public function testRender()
//    {
//
//    }

    public function testGetTemplatePlaceholders()
    {
        $placeholders = $this->documentTemplate->getTemplatePlaceholders();
        $this->assertEquals($this->expectedPlaceholders, $placeholders);
    }
}
