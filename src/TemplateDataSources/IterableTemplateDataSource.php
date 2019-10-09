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
     * @return array|Array
     */
    public function getTemplateData()
    {
        $templateData = [];
        foreach ($this->data as $dataSource) {
            $templateData[] = $dataSource->getData();
        }

        return $this->namespace ? [$this->getNameSpace() => $templateData] : $templateData;
    }

    /**
     * @return PlaceholderGroup
     */
    public function getPlaceholders()
    {
        $placeholders = [];

        foreach ($this->data as $dataSource) {
            $placeholders = $dataSource->getPlaceholders();
            break;
        }

        $placeholderGroup = new PlaceholderGroup(
            $this->getNameSpace(),
            $placeholders->getPlaceholders(),
            TYPE_ITERABLE_PLACEHOLDER
        );

        return $placeholderGroup;
    }
}
