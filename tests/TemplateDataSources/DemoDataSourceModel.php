<?php


namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\TemplateDataSources\DataSourceModel;

class DemoDataSourceModel extends DataSourceModel
{
    protected $namespace = 'Test source';

    protected $table = 'test_source';

}