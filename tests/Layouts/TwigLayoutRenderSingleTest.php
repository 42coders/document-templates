<?php

namespace BWF\DocumentTemplates\Tests\Layouts;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\Tests\TestCase;
use Twig\Error\RuntimeError;
use Twig\Sandbox\SecurityNotAllowedTagError;

class TwigLayoutRenderSingleTest extends TestCase
{

    public function testRenderSingle()
    {
        $user = [
            'email' => 'User Email'
        ];

        $editableTemplate = new EditableTemplate();
        $editableTemplate->setName('template');
        $editableTemplate->setContent('Subject {{user.email}}');

        $layout = new TwigLayout();
        $dataSources = [TemplateDataSourceFactory::build($user, 'user')];
        $rendered = $layout->renderSingle($editableTemplate, $dataSources);

        $this->assertEquals('Subject User Email', $rendered);

        $this->expectException(SecurityNotAllowedTagError::class);
        $editableTemplate->setContent('Subject {% if user.email %}{% endif %}');
        $layout->renderSingle($editableTemplate, $dataSources);
    }

    public function testTwigStrictVariables()
    {
        config(['document_templates.twig.environment.strict_variables' => true]);

        $user = [
            'email' => 'User Email'
        ];

        $editableTemplate = new EditableTemplate();
        $editableTemplate->setName('template');
        $editableTemplate->setContent('Subject {{user.name}}');

        $layout = new TwigLayout();
        $dataSources = [TemplateDataSourceFactory::build($user, 'user')];

        $this->expectException(RuntimeError::class);
        $layout->renderSingle($editableTemplate, $dataSources);
    }

    /**
     * @dataProvider provideScalarData
     */
    public function testRenderSingleWithScalarDataSource($expected, $nonIterable)
    {
        $editableTemplate = new EditableTemplate();
        $editableTemplate->setName('template');
        $editableTemplate->setContent('This is {{non_iterable}}');

        $layout = new TwigLayout();
        $dataSources = [TemplateDataSourceFactory::build($nonIterable, 'non_iterable')];
        $rendered = $layout->renderSingle($editableTemplate, $dataSources);

        $this->assertEquals($expected, $rendered);
    }

    public function provideScalarData()
    {
        yield ["This is string", "string"];
        yield ["This is 1", 1];
        yield ["This is 1.25", 1.25];
        yield ["This is 1", true];
        yield ["This is ", null];
    }

    public function testRenderWithoutNamespace()
    {
        $user = [
            'email' => 'User Email'
        ];

        $editableTemplate = new EditableTemplate();
        $editableTemplate->setName('template');
        $editableTemplate->setContent('Subject {{email}}');

        $layout = new TwigLayout();
        $dataSources = [TemplateDataSourceFactory::build($user)];
        $rendered = $layout->renderSingle($editableTemplate, $dataSources);

        $this->assertEquals('Subject User Email', $rendered);
    }
}
