<?php


namespace BWF\DocumentTemplates\TemplateDataSources;

class TemplateDataSource implements TemplateDataSourceInterface
{
    protected $namespace;

    use ProvidesTemplateData;

    /**
     * TemplateDataSource constructor.
     * @param $data
     * @param string $namespace
     */
    public function __construct($data, $namespace = '')
    {
        $this->namespace = $namespace;
        $this->data = $data;
    }
}
