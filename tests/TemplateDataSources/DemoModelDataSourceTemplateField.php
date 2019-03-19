<?php


namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\TemplateDataSources\ModelDataSource;

class DemoModelDataSourceTemplateField extends ModelDataSource
{
    protected $name = 'Test source';
    protected $table = 'test_source';

    protected function getTemplateFields()
    {
        return [
            'title',
            'name'
        ];
    }
}