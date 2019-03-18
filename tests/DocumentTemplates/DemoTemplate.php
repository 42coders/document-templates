<?php


namespace BWF\DocumentTemplates\Tests\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\Tests\Stubs\ArrayTemplateData;

class DemoTemplate extends DocumentTemplate
{
    use ArrayTemplateData;

    protected function dataSources()
    {
        return [
            $this->dataSource(collect([TemplateDataSourceFactory::build($this->testUsers[0], 'user')]), 'users'),
            $this->dataSource(collect([TemplateDataSourceFactory::build($this->testOrders[0], 'order')]), 'orders')
        ];
    }

}