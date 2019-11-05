<?php

namespace BWF\DocumentTemplates\Tests\Layouts;


use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Layouts\Layout;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\Tests\TestCase;

class TwigLayoutSimpleTest extends TestCase
{
    public function testGetName()
    {
        $layout = new TwigLayout();
        $layout->load('TestLayout.html.twig');

        $this->assertEquals('TestLayout.html.twig', $layout->getName());
    }

    public function testGetTemplates()
    {
        $layout = new TwigLayout();
        $this->assertInstanceOf(Layout::class, $layout);

        $layout->load('TestLayout.html.twig');

        $templates = $layout->getTemplates();
        $this->assertNotEmpty($templates);

        foreach ($templates as $template) {
            $this->assertInstanceOf(EditableTemplate::class, $template);
        }
    }

    public function testGetAvailableLayout()
    {
        $expectedResults = collect([
            0 => "TestLayout.html.twig",
            1 => "TestIterableDataSource.html.twig"
        ])->sort();

        $layout = new TwigLayout();
        $availableLayouts = $layout->getAvailableLayouts()->sort();

        $this->assertEquals($expectedResults, $availableLayouts);
    }

    public function testRender()
    {
        $layout = new TwigLayout();
        $layout->load('TestLayout.html.twig');
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

        $expectedOutput = file_get_contents(__DIR__ . '/../Stubs/TestLayout.expected.html');
        $output = $layout->render($templates, $dataSources);

        $this->assertEquals($expectedOutput, $output);
    }

    public function testRenderWithNotAllowedTag()
    {
        $layout = new TwigLayout();
        $layout->load('TestLayout.html.twig');
        $templates = $layout->getTemplates();

        foreach ($templates as $template) {
            switch ($template->getName()) {
                case 'title':
                    $template->setContent('{% if true %}If tag is not allowed{% endif %}' . PHP_EOL);
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

        $this->expectException(\Twig\Sandbox\SecurityNotAllowedTagError::class);

        $output = $layout->render($templates, $dataSources);
    }
}
