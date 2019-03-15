<?php

namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\TemplateDataSources\ArrayTemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\Tests\Stubs\ArrayTemplateData;
use BWF\DocumentTemplates\Tests\TestCase;

class ArrayTemplateDataSourceTest extends TestCase
{
    /**
     * @var ArrayTemplateDataSource
     */
    protected $dataSource;

    use ArrayTemplateData;

    protected $expectedTemplateData = [
        "users" => [
            [
                'id' => '1',
                'name' => 'first user name',
            ],
            [
                'id' => '2',
                'name' => 'second user name'
            ]
        ]
    ];

    protected $expectedPlaceholders = [
        "users" => [
            "user.id",
            "user.name"
        ]
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->dataSource = new ArrayTemplateDataSource($this->getTestUsers(), 'users');
    }

    public function testGetPlaceholders()
    {
        $placeholders = $this->dataSource->getPlaceholders();
        $this->assertEquals($this->expectedPlaceholders, $placeholders);
    }

    public function testGetTemplateData()
    {
        $templateData = $this->dataSource->getTemplateData();
        $this->assertEquals($this->expectedTemplateData, $templateData);
    }
}
