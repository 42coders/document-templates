<?php


namespace BWF\DocumentTemplates\DocumentTemplates;

use BWF\DocumentTemplates\Layouts\LayoutInterface;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;

abstract class DocumentTemplate implements DocumentTemplateInterface
{
    /**
     * @var \BWF\DocumentTemplates\Layouts\Layout
     */
    protected $layout;

    /**
     * Stores the datasource instances containing the renderable data. Used for template rendering.
     *
     * @var TemplateDataSource[]
     */
    private $templateData;

    protected function dataSources(){
        return [];
    }

    protected function dataSource($data, $name = '', $isIterable = false){
        if($isIterable){
            $data = collect([$data]);
        }
        return TemplateDataSourceFactory::build($data, $name);
    }

    public function setLayout(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }

    public function addTemplateData($data, $name = '')
    {
        $this->dataSources[] = TemplateDataSourceFactory::build($data, $name);
    }

    public function setTemplateData($data)
    {
        $this->templateData = $data;
    }

    public function getTemplatePlaceholders()
    {
        $placeholders = [];

        foreach ($this->dataSources() as $dataSource){
            $placeholders = array_merge($placeholders, $dataSource->getPlaceholders());
        }

        return $placeholders;
    }

    public function render()
    {
        // TODO: Implement render() method.
    }

    public function store()
    {
        // TODO: Implement store() method.
    }
}