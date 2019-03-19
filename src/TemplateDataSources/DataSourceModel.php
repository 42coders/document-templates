<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


use Illuminate\Database\Eloquent\Model;

abstract class DataSourceModel extends Model implements TemplateDataSourceInterface
{
    use ModelProvidesTemplateData;

    protected $name = '';
}