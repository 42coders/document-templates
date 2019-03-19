<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


trait ModelHasTemplateData
{
    use HasTemplateData;

    /**
     * @var array
     */
    protected $templateFields = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @return array
     */
    protected function getData(){
        $data = [];

        if(!empty($this->templateFields)){
            foreach ($this->templateFields as $field){
                $data[$field] = $this->{$field};
            }
        }
        else{
            $data = $this->attributes;
        }

        return $data;
    }
}