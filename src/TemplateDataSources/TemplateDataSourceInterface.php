<?php

namespace BWF\DocumentTemplates\TemplateDataSources;

interface TemplateDataSourceInterface
{
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

    /**
     * @param $namespace
     */
    public function setNamespace($namespace);

    /**
     * @return string
     */
    public function getNamespace();
}
