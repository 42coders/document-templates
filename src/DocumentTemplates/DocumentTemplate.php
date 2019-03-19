<?php


namespace BWF\DocumentTemplates\DocumentTemplates;

use BWF\DocumentTemplates\Layouts\LayoutInterface;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface;
use Illuminate\Database\Eloquent\Model;

abstract class DocumentTemplate extends Model implements DocumentTemplateInterface
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
    private $templateData = [];

    protected function dataSources()
    {
        return [];
    }

    /**
     * @param array|\stdClass $data
     * @param string $name
     * @param bool $isIterable
     * @param string $iterableName Use as an iterable variable in the template
     * @return TemplateDataSource
     */
    protected function dataSource($data, $name = '', $isIterable = false, $iterableName = '')
    {
        $templateDataSource = TemplateDataSourceFactory::build($data, $name);
        if ($isIterable) {
            $templateDataSource = collect([$templateDataSource]);
            $templateDataSource = TemplateDataSourceFactory::build($templateDataSource, $iterableName);
        }

        return $templateDataSource;
    }

    public function setLayout(LayoutInterface $layout)
    {
        $this->layout = $layout;
    }

    public function addTemplateData($data, $name = '')
    {
        $this->templateData[] = TemplateDataSourceFactory::build($data, $name);
    }

    public function setTemplateData($data)
    {
        $this->templateData = $data;
    }

    public function getTemplatePlaceholders()
    {
        $placeholders = [];

        foreach ($this->dataSources() as $dataSource) {
            $placeholders = array_merge($placeholders, $dataSource->getPlaceholders());
        }

        return $placeholders;
    }

    /**
     * @return array
     */
    protected function getTemplates()
    {
        //TODO: Load templates from the database
        return [];
    }

    public function render()
    {
        return $this->layout->render($this->getTemplates(), $this->templateData);
    }

    public function store()
    {
        // TODO: Implement store() method.
    }
}