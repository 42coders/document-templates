<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


trait ModelProvidesTemplateData
{
    use ProvidesTemplateData;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @return array
     */
    protected function getTemplateFields()
    {
        return array_keys($this->attributes);
    }

    /**
     * @return array
     */
    protected function getData()
    {
        $data = [];
        $templateFields = $this->getTemplateFields();

        foreach ($templateFields as $field) {
            $data[$field] = $this->{$field};
        }

        return $data;
    }
}