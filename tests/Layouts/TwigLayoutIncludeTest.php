<?php

namespace BWF\DocumentTemplates\Tests\Layouts;


use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Layouts\Layout;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\Tests\TestCase;

class TwigLayoutSimpleTest extends TestCase
{
    public function testRender()
    {
        $layout = new TwigLayout();
        $layout->load('TestLayoutWithInclude.html.twig');
        $templates = $layout->getTemplates();

        foreach ($templates as $template) {
            switch ($template->getName()) {
                case 'title':
                    $template->setContent('This is the title: {{title_source.title}}' . PHP_EOL);
                    break;
                case 'content':
                    $template->setContent('<p>Hello {{name_source.name}}, this is the content!</p>' . PHP_EOL);
                    break;
            }
        }

        $dataSources = [];

        $dataSources[] = new TemplateDataSource(['title' => 'Testing the layout render'], 'Title Source');
        $dataSource = new TemplateDataSource(['name' => 'Layout Test']);
        $dataSource->setNamespace('Name source');
        $dataSources[] = $dataSource;

        $expectedOutput = file_get_contents(__DIR__ . '/../Stubs/TestLayoutWithInclude.expected.html');
        $output = $layout->render($templates, $dataSources);

        $this->assertEquals($expectedOutput, $output);
    }
}
