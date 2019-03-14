<?php

namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
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
        $this->dataSource = new TemplateDataSource($this->testData, 'Test Source');
    }

    public function testGetPlaceholders()
    {
        $templateData = $this->dataSource->getTemplateData();
        $this->assertEquals($this->expectedTemplateData, $templateData);
    }

    public function testGetDataSources()
    {
        $placeholders = $this->dataSource->getPlaceholders();
        $this->assertEquals($this->expectedPlaceholders, $placeholders);
    }
}
