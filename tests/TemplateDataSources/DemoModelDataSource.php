<?php


namespace BWF\DocumentTemplates\Tests\TemplateDataSources;


use BWF\DocumentTemplates\TemplateDataSources\ModelProvidesTemplateData;
use Illuminate\Database\Eloquent\Model;

class DemoModelDataSource extends Model
{
    use ModelProvidesTemplateData;

    protected $table = 'test_source';

    protected function getTemplateFields(){
        return [
            'title',
            'name'
        ];
    }
}