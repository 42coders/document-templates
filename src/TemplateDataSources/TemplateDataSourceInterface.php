<?php

namespace BWF\DocumentTemplates\TemplateDataSources;

interface TemplateDataSourceInterface
{
    /**
     * TemplateDataSource constructor.
     * @param Array|\stdClass $data
     * @param string $name
     */
    public function __construct($data, $name = null);

    /**
     * Return the data to use in the template if the $useNamespace is true
     * the $this->name is used in the array as a key.
     *
     * @param boolean $useNamespace
     * @return array
     */
    public function getTemplateData($useNamespace = true);

    /**
     * @return string[]
     */
    public function getPlaceholders();
}