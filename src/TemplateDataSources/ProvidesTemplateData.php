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
    public function getName()
    {
        return $this->canonise($this->name ?? '');
    }

    /**
     * @param string|null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param bool $useNamespace
     * @return array
     */
    public function getTemplateData($useNamespace = true)
    {
        $data = $this->getData();
        $name = $this->getName();

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
        if ($this->name) {
            $placeholder = $this->getName() . '.' . $key;
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