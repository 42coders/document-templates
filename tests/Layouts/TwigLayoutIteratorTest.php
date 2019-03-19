<?php

namespace BWF\DocumentTemplates\Tests\Layouts;

use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\TemplateDataSources\IterableTemplateDataSource;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;
use BWF\DocumentTemplates\Tests\TestCase;

class TwigLayoutIteratorTest extends TestCase
{

    use IterableTemplateData;

    public function testRender()
    {
        $layout = new TwigLayout();
        $layout->load(__DIR__ . '/../Stubs/TestIterableDataSource.html.twig');
        $templates = $layout->getTemplates();

        foreach ($templates as $template) {
            switch($template->getName()) {
                case 'user_table_rows':
                    $template->setContent('{% for user in users %}<tr><td>{{user.id}}</td><td>{{user.name}}</td></tr>{% endfor %}' . PHP_EOL . PHP_EOL);
                    break;
                case 'order_table_rows':
                    $template->setContent('{% for order in orders %}<tr><td>{{order.id}}</td><td>{{order.description}}</td></tr>{% endfor %}' . PHP_EOL . PHP_EOL);
                    break;
            }
        }

        $dataSources = [];

        $dataSources[] = new IterableTemplateDataSource($this->getTestUsers(), 'users');
        $dataSources[] = new IterableTemplateDataSource($this->getTestOrders(), 'orders');

        $expectedOutput = file_get_contents(__DIR__ . '/../Stubs/TestIterableDataSource.expected.html');
        $output = $layout->render($templates, $dataSources);

        $this->assertEquals($expectedOutput, $output);
    }
}
