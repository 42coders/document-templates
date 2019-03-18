<?php

namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\Tests\TestCase;

class TemplateDataSourceFactoryTest extends TestCase
{

    public function testBuildDataSource()
    {
        $templateDataSource = TemplateDataSourceFactory::build(['test' => 'data'], 'test-name');
        $this->assertInstanceOf(TemplateDataSource::class, $templateDataSource);

        $testDataObject = new \stdClass();
        $testDataObject->test = 'data';

        $templateDataSource = TemplateDataSourceFactory::build($testDataObject, 'test-name');
        $this->assertInstanceOf(TemplateDataSource::class, $templateDataSource);
    }

    public function testBuildIterableDataSource()
    {
        $collection = collect(['test' => 'data']);

        $templateDataSource = TemplateDataSourceFactory::build($collection, 'test-name');
        $this->assertInstanceOf(TemplateDataSource::class, $templateDataSource);
    }
}
