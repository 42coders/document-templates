<?php

namespace BWF\DocumentTemplates\Tests\Layouts;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\Tests\TestCase;
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
}
