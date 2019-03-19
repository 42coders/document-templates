<?php

namespace BWF\DocumentTemplates\Tests\TemplateDataSources;


use BWF\DocumentTemplates\Tests\TestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoModelDataSourceTest extends TestCase
{
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
    }

    public function testGetTemplateData()
    {
        $expectedTemplateData = [
            "test_source" => [
                "id" => 1,
                "title" => "Testing the layout render",
                "name" => "Layout Test"
            ]
        ];

        $demoDataSource = new DemoModelDataSource();
        $demoDataSource = $demoDataSource->first();

        $templateData = $demoDataSource->getTemplateData();
        $this->assertEquals($expectedTemplateData, $templateData);
    }

    public function testGetPlaceholders()
    {
        $expectedPlaceholders = [
            "test_source.id",
            "test_source.title",
            "test_source.name"
        ];

        $demoDataSource = new DemoModelDataSource();
        $demoDataSource = $demoDataSource->first();

        $placeholders = $demoDataSource->getPlaceholders();
        $this->assertEquals($expectedPlaceholders, $placeholders);
    }


    public function testGetPlaceholdersWithTemplateField()
    {
        $expectedPlaceholders = [
            "test_source.title",
            "test_source.name"
        ];

        $demoDataSource = new DemoModelDataSourceTemplateField();
        $demoDataSource = $demoDataSource->first();

        $placeholders = $demoDataSource->getPlaceholders();

        $this->assertEquals($expectedPlaceholders, $placeholders);
    }

    public function testGetTemplateDataWithTemplateField()
    {
        $expectedTemplateData = [
            "test_source" => [
                "title" => "Testing the layout render",
                "name" => "Layout Test"
            ]
        ];

        $demoDataSource = new DemoModelDataSourceTemplateField();
        $demoDataSource = $demoDataSource->first();

        $templateData = $demoDataSource->getTemplateData();
        $this->assertEquals($expectedTemplateData, $templateData);
    }
}
