<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


use BWF\DocumentTemplates\TemplateDataSources\ModelProvidesTemplateData;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface;
use Illuminate\Database\Eloquent\Model;

abstract class ModelDataSource extends Model implements TemplateDataSourceInterface
{
    use ModelProvidesTemplateData;

    protected $name = '';
}