<?php

namespace Layouts;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Exceptions\InvalidTwigExtension;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\Tests\Layouts\TestTwigExtension;
use BWF\DocumentTemplates\Tests\TestCase;
use Twig\Extension\CoreExtension;
use Twig\Sandbox\SecurityNotAllowedTagError;

class TwigExtensionTest extends TestCase
{
    public function testIfInvalidTwigExtensionExceptionIsThrown()
    {
        $this->expectException(InvalidTwigExtension::class);

        config([
            'document_templates.twig.extensions' => [
                CoreExtension::class,
                TemplateDataSource::class,
            ],
        ]);

        new TwigLayout();
    }

    public function testIfExtensionIsLoadedAndWorks()
    {
        config([
            'document_templates.twig.extensions' => [
                TestTwigExtension::class,
            ],
            'document_templates.template_sandbox.allowedFunctions' => [
                'return_twig'
            ]
        ]);

        $editableTemplate = new EditableTemplate();
        $editableTemplate->setName('template');
        $editableTemplate->setContent('It should return {{ return_twig() }}');

        $layout = new TwigLayout();
        $rendered = $layout->renderSingle($editableTemplate, []);

        $this->assertEquals('It should return Twig', $rendered);
    }
}
