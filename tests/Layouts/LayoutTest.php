<?php

namespace BWF\DocumentTemplates\Tests\Layouts;


use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Layouts\Layout;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\Tests\TestCase;

class LayoutTest extends TestCase
{
    public function testGetTemplates()
    {
        $layout = new TwigLayout();
        $this->assertInstanceOf(Layout::class, $layout);

        $layout->load(__DIR__ . '/../Stubs/TestLayout.html.twig');

        $templates = $layout->getTemplates();
        $this->assertNotEmpty($templates);

        foreach ($templates as $template){
            $this->assertInstanceOf(EditableTemplate::class, $template);
        }
    }

    public function testRender()
    {
        $layout = new TwigLayout();
        $layout->load(__DIR__ . '/../Stubs/TestLayout.html.twig');
        $templates = $layout->getTemplates();

        foreach ($templates as $template){
            switch($template->getName()){
                case 'title':
                    $template->setContent('This is the title: {{test_source.title}}'.PHP_EOL);
                    break;
                case 'content':
                    $template->setContent('<p>Hello {{test_source.name}}, this is the content!</p>'.PHP_EOL);
                    break;
            }
        }

        $data = [
            'name' => 'Layout Test',
            'title' => 'Testing the layout render'
        ];

        $dataSource = new TemplateDataSource($data, 'Test Source');

        $expectedOutput = file_get_contents(__DIR__ . '/../Stubs/TestLayout.expected.html');
        $output = $layout->render($templates, [$dataSource]);

        $this->assertEquals($expectedOutput, $output);
    }
}
