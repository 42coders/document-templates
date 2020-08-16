<?php

namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\Exceptions\MissingNamespaceException;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface;
use BWF\DocumentTemplates\Tests\TestCase;
use stdClass;

class TemplateDataSourceFactoryTest extends TestCase
{

    public function testBuildDataSource()
    {
        $templateDataSource = TemplateDataSourceFactory::build(['test' => 'data'], 'test-name');
        $this->assertInstanceOf(TemplateDataSource::class, $templateDataSource);

        $testDataObject = new stdClass();
        $testDataObject->test = 'data';

        $templateDataSource = TemplateDataSourceFactory::build($testDataObject, 'test-name');
        $this->assertInstanceOf(TemplateDataSource::class, $templateDataSource);

        $templateDataSource = TemplateDataSourceFactory::build('string', 'test-name');
        $this->assertInstanceOf(TemplateDataSource::class, $templateDataSource);
    }

    public function testBuildIterableDataSource()
    {
        $collection = collect(['test' => 'data']);

        $templateDataSource = TemplateDataSourceFactory::build($collection, 'test-name');
        $this->assertInstanceOf(TemplateDataSource::class, $templateDataSource);
    }

    public function testBuildFromModel()
    {
        $model = new DemoDataSourceModel();

        $templateDataSource = TemplateDataSourceFactory::build($model, 'test-name');
        $this->assertEquals($templateDataSource->getNameSpace(), 'test-name');
        $this->assertInstanceOf(TemplateDataSourceInterface::class, $templateDataSource);
    }
}
