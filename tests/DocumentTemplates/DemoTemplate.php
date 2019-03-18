<?php


namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\Tests\Stubs\ArrayTemplateData;

class DemoTemplate extends DocumentTemplate
{
    use ArrayTemplateData;

    protected function dataSources()
    {
        $testOrderObject = new \stdClass();
        $testOrderObject->id = 1;
        $testOrderObject->description = 'first order description';

        return [
            $this->dataSource($this->testUsers[0], 'user', true, 'users'),
            $this->dataSource($testOrderObject, 'order', true, 'orders'),
            $this->dataSource($this->testOrders[0], 'order'),
        ];
    }

}