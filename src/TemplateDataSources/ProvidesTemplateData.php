<?php


namespace BWF\DocumentTemplates\TemplateDataSources;

use BWF\DocumentTemplates\Exceptions\MissingNamespaceException;

trait ProvidesTemplateData
{
    /**
     * @var array|Array
     */
    protected $data = [];

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string|null
     */
    public function getNameSpace()
    {
        return $this->canonise($this->namespace ?? '');
    }

    /**
     * @param string|null $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return array
     */
    public function getTemplateData()
    {
        $data = $this->getData();
        $name = $this->getNameSpace();

        return  $name ? [$name => $data] : $data;
    }

    /**
     * @param $name
     * @return string
     */
    protected function canonise($name)
    {
        return strtolower(strtr($name, ' ', '_'));
    }

    /**
     * @param $key
     * @return string
     */
    protected function createPlaceholder($key = null)
    {
        if (empty($this->namespace) && empty($key)) {
            throw new MissingNamespaceException(
                'The scalar placeholders (string, integer) must define a namespace!'
            );
        }

        if (empty($key)) {
            return $this->getNameSpace();
        }

        if ($this->namespace) {
            return $this->getNameSpace() . '.' . $key;
        }

        return $key;
    }

    /**
     * @return \BWF\DocumentTemplates\TemplateDataSources\PlaceholderGroup
     */
    public function getPlaceholders()
    {
        $placeholders = $this->createPlaceholders();

        return new PlaceholderGroup($this->getNameSpace(), $placeholders, TYPE_SINGLE_PLACEHOLDER);
    }

    /**
     * @return array
     */
    protected function createPlaceholders(): array
    {
        $data = $this->getData();

        if (!is_array($data) && !is_object($data)) {
            return [$this->createPlaceholder()];
        }

        $placeholders = [];
        foreach ($data as $key => $item) {
            $placeholders[] = $this->createPlaceholder($key);
        }
        return $placeholders;
    }
}
