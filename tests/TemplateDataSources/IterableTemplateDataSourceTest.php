<?php

namespace BWF\DocumentTemplates\Tests\TemplateDataSources;

use BWF\DocumentTemplates\TemplateDataSources\IterableTemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;
use BWF\DocumentTemplates\Tests\TestCase;

class IterableTemplateDataSourceTest extends TestCase
{
    /**
     * @var IterableTemplateDataSource
     */
    protected $dataSource;

    use IterableTemplateData;

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
        "user.id",
        "user.name"
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->dataSource = new IterableTemplateDataSource(collect($this->getTestUsers()), 'users');
    }

    public function testGetPlaceholders()
    {
        $placeholders = $this->dataSource->getPlaceholders();
        $this->assertEquals($this->expectedPlaceholders, $placeholders->getPlaceholders());
    }

    public function testGetTemplateData()
    {
        $templateData = $this->dataSource->getTemplateData();
        $this->assertEquals($this->expectedTemplateData, $templateData);
    }
}
