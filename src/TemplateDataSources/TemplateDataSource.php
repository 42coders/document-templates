<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


class TemplateDataSource implements TemplateDataSourceInterface
{
    use ProvidesTemplateData;

    /**
     * TemplateDataSource constructor.
     * @param array $data
     * @param string $name
     */
    public function __construct($data, $name = null)
    {
        $this->name = $name;
        $this->data = $data;
    }
}