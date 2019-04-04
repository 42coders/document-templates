<?php

namespace BWF\DocumentTemplates\Tests\DocumentTemplates;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateFactory;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateInterface;
use BWF\DocumentTemplates\Tests\TestCase;

class DocumentTemplateFactoryTest extends TestCase
{

    public function testBuild()
    {
        $documentTemplateModel = new DemoDocumentTemplateModel();

        $documentTemplateData = [
            'name' => '',
            'document_class' => DemoDocumentTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ];

        $documentTemplateModel->fill($documentTemplateData);

        $documentTemplate = DocumentTemplateFactory::build($documentTemplateModel);

        $this->assertInstanceOf(DocumentTemplateInterface::class, $documentTemplate);
    }

    public function testBuildWithBadClass()
    {
        $documentTemplateModel = new DemoDocumentTemplateModel();

        $documentTemplateData = [
            'name' => '',
            'document_class' => \stdClass::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ];

        $documentTemplateModel->fill($documentTemplateData);

        $this->expectException(\Exception::class);
        DocumentTemplateFactory::build($documentTemplateModel);
    }
}
