<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


trait ModelProvidesTemplateData
{
    use ProvidesTemplateData;

    /**
     * @return array
     */
    protected function getTemplateFields(){
        return [];
    }

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @return array
     */
    protected function getData(){
        $data = [];
        $templateFields = $this->getTemplateFields();

        if(!empty($templateFields)){
            foreach ($templateFields as $field){
                $data[$field] = $this->attributes[$field];
            }
        }
        else{
            $data = $this->attributes;
        }

        return $data;
    }
}