<?php


namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\TemplateDataSources\ModelDataSource;

class DemoModelDataSource extends ModelDataSource
{
    protected $name = 'Test source';

    protected $table = 'test_source';

}