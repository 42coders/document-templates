<?php


namespace BWF\DocumentTemplates\TemplateDataSources;

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
    protected function createPlaceholder($key)
    {
        $placeholder = $key;
        if ($this->namespace) {
            $placeholder = $this->getNameSpace() . '.' . $key;
        }

        return $placeholder;
    }

    /**
     * @return \BWF\DocumentTemplates\TemplateDataSources\PlaceholderGroup
     */
    public function getPlaceholders()
    {
        $placeholders = [];
        foreach ($this->getData() as $key => $item) {
            $placeholders[] = $this->createPlaceholder($key);
        }

        return new PlaceholderGroup($this->getNameSpace(), $placeholders, TYPE_SINGLE_PLACEHOLDER);
    }
}
