<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


class TemplateDataSourceFactory
{
    /**
     * @param array|object $data
     * @param string $name
     * @return TemplateDataSource
     */
    public static function build($data, $name = ''){
        $templateDatasource = new TemplateDataSource($data, $name);

        if($data instanceof \IteratorAggregate){
            $templateDatasource = new IterableTemplateDataSource($data, $name);
        }

        return $templateDatasource;
    }
}