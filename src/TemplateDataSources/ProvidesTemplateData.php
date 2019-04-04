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
    protected function getData(){
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
     * @param bool $useNamespace
     * @return array
     */
    public function getTemplateData($useNamespace = true)
    {
        $data = $this->getData();
        $name = $this->getNameSpace();

        if ($useNamespace) {
            $data = $name ? [$name => $data] : $data;
        }
        return $data;
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
     * @return array|string[]
     */
    public function getPlaceholders()
    {
        $placeholders = [];
        foreach ($this->getData() as $key => $item) {
            $placeholders[] = $this->createPlaceholder($key);
        }

        return $placeholders;
    }
}