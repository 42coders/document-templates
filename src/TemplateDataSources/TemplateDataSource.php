<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


class TemplateDataSource implements TemplateDataSourceInterface
{
    protected $name = null;
    /**
     * @var array|Array
     */
    protected $data = [];

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

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->canonise($this->name);
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
        $data = $this->data;

        if ($useNamespace) {
            $data = $this->name ? [$this->getName() => $this->data] : $this->data;
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
        foreach ($this->data as $key => $item) {
            $placeholders[] = $this->createPlaceholder($key);
        }

        return $placeholders;
    }
}