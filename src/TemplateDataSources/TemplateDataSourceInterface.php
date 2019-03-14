<?php

namespace BWF\DocumentTemplates\TemplateDataSources;

interface TemplateDataSourceInterface
{
    /**
     * TemplateDataSource constructor.
     * @param Array $data
     * @param string $name
     */
    public function __construct($data, $name = null);

    /**
     * @return Array
     */
    public function getTemplateData();

    /**
     * @return string[]
     */
    public function getPlaceholders();
}