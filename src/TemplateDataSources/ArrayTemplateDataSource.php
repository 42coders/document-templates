<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


class ArrayTemplateDataSource extends TemplateDataSource implements TemplateDataSourceInterface
{
    /**
     * @var TemplateDataSource[]
     */
    protected $data = [];

    /**
     * ArrayTemplateDataSource constructor.
     * @param TemplateDataSource[] $data
     * @param null $name
     */
    public function __construct($data, $name = null)
    {
        parent::__construct($data, $name);
    }

    /**
     * @param bool $useNamespace
     * @return array|Array
     */
    public function getTemplateData($useNamespace = true)
    {
        $templateData = [];
        foreach ($this->data as $dataSource) {
            $templateData[] = $dataSource->getTemplateData(false);
        }

        $data = $templateData;

        if ($useNamespace) {
            $data = $this->name ? [$this->getName() => $templateData] : $templateData;
        }

        return $data;
    }

    /**
     * @return array|string[]
     */
    public function getPlaceholders()
    {
        $placeholders = [];

        foreach ($this->data as $dataSource) {
            $placeholders = $dataSource->getPlaceholders();
            break;
        }

        if ($this->name) {
            $placeholders = [
                $this->getName() => $placeholders
            ];
        }

        return $placeholders;
    }

}