<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


class IterableTemplateDataSource extends TemplateDataSource implements TemplateDataSourceInterface
{
    /**
     * @var TemplateDataSource[]
     */
    protected $data = [];

    /**
     * ArrayTemplateDataSource constructor.
     * @param TemplateDataSource[] $data
     * @param null $namespace
     */
    public function __construct($data, $namespace = null)
    {
        parent::__construct($data, $namespace);
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
            $data = $this->namespace ? [$this->getNameSpace() => $templateData] : $templateData;
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

        if ($this->namespace) {
            $placeholders = [
                $this->getNameSpace() => $placeholders
            ];
        }

        return $placeholders;
    }

}