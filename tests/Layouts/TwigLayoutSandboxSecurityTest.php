<?php

namespace BWF\DocumentTemplates\Tests\Layouts;


use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Layouts\Layout;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\Tests\TestCase;
use Illuminate\Foundation\Auth\User;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Sandbox\SecurityNotAllowedMethodError;
use Twig\Sandbox\SecurityNotAllowedPropertyError;
use Twig\Sandbox\SecurityNotAllowedTagError;

class TwigLayoutSandboxSecurityTest extends TestCase
{
    /**
     * @param $content
     * @param $expectedException
     * @dataProvider sandboxNotAllowedDataProvider
     */
    public function testSandboxNotAllowed($content, $expectedException)
    {
        config(['document_templates.template_sandbox.allowedProperties' => []]);

        $user = new User();
        $user->email = 'User Email';
        $user->incrementing = true;

        $editableTemplate = new EditableTemplate();
        $editableTemplate->setName('template');
        $editableTemplate->setContent($content);

        $layout = new TwigLayout();
        $dataSources = [TemplateDataSourceFactory::build($user, 'user')];

        $this->expectException($expectedException);
        $layout->renderSingle($editableTemplate, $dataSources);
    }

    public function sandboxNotAllowedDataProvider()
    {
        return [
            'not-allowed-tag' => [
                '{% if true %}If tag is not allowed{% endif %}',
                SecurityNotAllowedTagError::class
            ],
            'not-allowed-filter' => [
                'Name: {{user.email|json_encode()}}',
                SecurityNotAllowedFilterError::class
            ],
            'not-allowed-function' => [
                'Random number: {{random(5)}}',
                SecurityNotAllowedFunctionError::class
            ],
            'not-allowed-method' => [
                'Not allowed method: {{user.toJson()}}',
                SecurityNotAllowedMethodError::class
            ],
            'not-allowed-property' => [
                'Not allowed property: {{user.incrementing}}',
                SecurityNotAllowedPropertyError::class
            ]
        ];
    }

    /**
     * @param string $content
     * @param string $expected
     * @dataProvider sandboxAllowedDataProvider
     */
    public function testSandboxAllowed($content, $expected)
    {
        config([
            'document_templates.template_sandbox' => [
                'allowedTags' => ['if'],
                'allowedFilters' => ['escape', 'capitalize'],
                'allowedMethods' => [User::class => ['toJson']],
                'allowedProperties' => [User::class => ['incrementing']],
                'allowedFunctions' => ['min']
            ]
        ]);

        $user = new User();
        $user->email = 'User Email';
        $user->incrementing = true;

        $markup = new Markup('','');

        $editableTemplate = new EditableTemplate();
        $editableTemplate->setName('template');
        $editableTemplate->setContent($content);

        $layout = new TwigLayout();
        $dataSources = [
            TemplateDataSourceFactory::build($user, 'user'),
            TemplateDataSourceFactory::build($markup, 'markup'),];

        $rendered = $layout->renderSingle($editableTemplate, $dataSources);
        $this->assertEquals($expected, $rendered);
    }

    public function testSandboxWildCardPropertiesAllowed()
    {
        config([
            'document_templates.template_sandbox.allowedProperties' => ['*']
        ]);

        $user = new User();
        $user->email = 'User Email';
        $user->incrementing = true;

        $editableTemplate = new EditableTemplate();
        $editableTemplate->setName('template');
        $editableTemplate->setContent('Any property is enabled: {{user.incrementing}}');

        $layout = new TwigLayout();
        $dataSources = [
            TemplateDataSourceFactory::build($user, 'user')
        ];

        $rendered = $layout->renderSingle($editableTemplate, $dataSources);
        $this->assertEquals('Any property is enabled: 1', $rendered);
    }

    public function sandboxAllowedDataProvider()
    {
        return [
            'allowed-tag' => [
                '{% if true %}If tag is allowed{% endif %}',
                'If tag is allowed'
            ],
            'allowed-filter' => [
                'Name: {{\'john\'|capitalize}}',
                'Name: John'
            ],
            'allowed-function' => [
                'Min number: {{min(1, 3, 2)}}',
                'Min number: 1'
            ],
            'allowed-method' => [
                'Allowed method: {{user.toJson()}}',
                'Allowed method: {"email":"User Email"}'
            ],
            'allowed-property' => [
                'Allowed property: {{user.incrementing}}',
                'Allowed property: 1'
            ],
            'all-methods-allowed-for-markup' => [
                'Allowed property: {{markup.jsonSerialize}}',
                'Allowed property: ',
            ]
        ];
    }
}
