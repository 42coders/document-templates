<?php

namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\Tests\TestCase;

class DocumentTemplateCrudTest extends TestCase
{
    public function testCreate()
    {
        $expectedLayouts = [
            0 => "TestLayout.html.twig",
            1 => "TestIterableDataSource.html.twig"
        ];

        $layout = new TwigLayout();
        $availableLayouts = $layout->getAvailableLayouts();

        $this->assertEquals($expectedLayouts, $availableLayouts);

        $documentTemplate = new DemoDocumentTemplate();
        $documentTemplate->init();

        $layout->load($availableLayouts[0]);
        $documentTemplate->setLayout($layout);

        $templates = $documentTemplate->getTemplates();

        $this->assertCount(2, $templates);
        $this->assertTrue($templates->contains(
            function (EditableTemplate $value) {
                return $value->getName() == 'title';
            })
        );

        $this->assertTrue($templates->contains(
            function (EditableTemplate $value) {
                return $value->getName() == 'content';
            })
        );

        $layout->load($availableLayouts[1]);
        $documentTemplate->setLayout($layout);

        $templates = $documentTemplate->getTemplates();


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

    public function testGetClasses()
    {
        $classes = collect([
            'class1',
            'class2',
            DemoDocumentTemplate::class
        ]);

        $expectedClasses = collect([
                'class1',
                'class2',
            ]
        );

        $documentTemplateModel = new DemoDocumentTemplateModel();

        $documentTemplateData = [
            'name' => '',
            'document_class' => DemoDocumentTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ];

        $documentTemplateModel->fill($documentTemplateData);
        $documentTemplateModel->save();

        $savedClasses = DocumentTemplateModel::all()->pluck('document_class');
        $availableClasses = $classes->diff($savedClasses);

        $this->assertEquals($expectedClasses, $availableClasses);
    }
}
