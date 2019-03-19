<?php


namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;

class DemoDocumentTemplate extends DocumentTemplate
{
    use IterableTemplateData;

    protected function dataSources()
    {
        $testObject = new \stdClass();
        $testObject->title = '';
        $testObject->name = '';

        return [
            $this->dataSource($this->testUsers[0], 'user', true, 'users'),
            $this->dataSource($this->testOrders[0], 'order', true, 'orders'),
            $this->dataSource($testObject, 'test'),
        ];
    }

    protected function getTemplates()
    {
        $templates = $this->layout->getTemplates();

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

        return $templates;
    }

}