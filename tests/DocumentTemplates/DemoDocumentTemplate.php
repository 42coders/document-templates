<?php


namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateInterface;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;

class DemoDocumentTemplate implements DocumentTemplateInterface
{
    use DocumentTemplate;
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

}