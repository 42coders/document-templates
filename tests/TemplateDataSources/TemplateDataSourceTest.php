<?php

namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\Exceptions\MissingNamespaceException;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\Tests\TestCase;

class TemplateDataSourceTest extends TestCase
{

    /**
     * @var TemplateDataSource
     */
    protected $dataSource;

    protected $testData = [
        'title' => 'Testing the layout render',
        'name' => 'Layout Test'
    ];

    /**
     * @var \stdClass
     */
    protected $testDataObject = null;

    protected $expectedTemplateData = [
        "test_source" => [
            "title" => "Testing the layout render",
            "name" => "Layout Test"
        ]
    ];

    protected $expectedPlaceholders = [
        "test_source.title",
        "test_source.name"
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->testDataObject = new \stdClass();
        $this->testDataObject->title = 'Testing the layout render';
        $this->testDataObject->name = 'Layout Test';

        $this->dataSource = new TemplateDataSource($this->testData, 'Test Source');
    }

    public function testGetDataSources()
    {
        $templateData = $this->dataSource->getTemplateData();
        $this->assertEquals($this->expectedTemplateData, $templateData);
    }

    public function testGetPlaceholders()
    {
        $placeholders = $this->dataSource->getPlaceholders();
        $this->assertEquals($this->expectedPlaceholders, $placeholders->getPlaceholders());
    }

    public function testGetPlaceholdersFromObject()
    {
        $this->dataSource = new TemplateDataSource($this->testDataObject, 'Test Source');

        $placeholders = $this->dataSource->getPlaceholders();
        $this->assertEquals($this->expectedPlaceholders, $placeholders->getPlaceholders());
    }

    public function testGetPlaceholdersFromObjectWithoutNamespace()
    {
        $this->dataSource = new TemplateDataSource($this->testDataObject);

        $placeholders = $this->dataSource->getPlaceholders();
        $this->assertEquals(['title', 'name'], $placeholders->getPlaceholders());
    }

    public function testGetPlaceholdersFromString()
    {
        $this->dataSource = new TemplateDataSource('String as test data', 'Test String');

        $placeholders = $this->dataSource->getPlaceholders();
        $this->assertEquals(['test_string'], $placeholders->getPlaceholders());
    }

    public function testGetPlaceholdersFromScalarVariableWithoutNamespaceThrowsException()
    {
        $this->expectException(MissingNamespaceException::class);
        $templateDataSource = new TemplateDataSource('String as test data');

        $templateDataSource->getPlaceholders();
    }
}
