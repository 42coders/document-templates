<?php


namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\TemplateDataSources\DataSourceModel;

class DemoDataSourceModelTemplateField extends DataSourceModel
{
    protected $namespace = 'Test source';
    protected $table = 'test_source';

    protected function getTemplateFields()
    {
        return [
            'title',
            'name'
        ];
    }
}