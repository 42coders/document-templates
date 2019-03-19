<?php

namespace BWF\DocumentTemplates\Tests\TemplateDataSources;


use BWF\DocumentTemplates\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoModelDataSourceTest extends TestCase
{
    /**
     * @var Model
     */
    protected $dataSource;

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

        Schema::create('test_source', function ($table) {
            $table->integer('id');
            $table->string('title');
            $table->string('name');
        });

        DB::table('test_source')->insert([
            "id" => 1,
            "title" => "Testing the layout render",
            "name" => "Layout Test"
        ]);

        $demoDataSource = new DemoModelDataSource();

        $this->dataSource = $demoDataSource->first();
        $this->dataSource->setName('Test source');
    }

    public function testGetDataSources()
    {
        $templateData = $this->dataSource->getTemplateData();
        $this->assertEquals($this->expectedTemplateData, $templateData);
    }

    public function testGetPlaceholders()
    {
        $placeholders = $this->dataSource->getPlaceholders();
        $this->assertEquals($this->expectedPlaceholders, $placeholders);
    }
}
