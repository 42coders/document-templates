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
        $templateDataSource = $data;
        $buildRequired = false;

        if(is_array($data)){
            $buildRequired = true;
        }
        else if(is_object($data)) {
            $reflexionData = new \ReflectionObject($data);
            if(!$reflexionData->implementsInterface(TemplateDataSourceInterface::class)){
                $buildRequired = true;
            }
        }

        if($buildRequired){
            if($data instanceof \IteratorAggregate){
                $templateDataSource = new IterableTemplateDataSource($data, $name);
            }
            else{
                $templateDataSource = new TemplateDataSource($data, $name);
            }
        }

        return $templateDataSource;
    }
}