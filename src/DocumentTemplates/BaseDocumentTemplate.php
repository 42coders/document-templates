<?php


namespace BWF\DocumentTemplates\DocumentTemplates;

use BWF\DocumentTemplates\Exceptions\InvalidClassException;
use BWF\DocumentTemplates\Layouts\LayoutInterface;
use BWF\DocumentTemplates\Renderers\PdfRendererInterface;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceFactory;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface;

trait BaseDocumentTemplate
{
    /**
     * @var \BWF\DocumentTemplates\Layouts\Layout
     */
    protected $layout;

    /**
     * @var \BWF\DocumentTemplates\Renderers\Renderer
     */
    protected $renderer;

    /**
     * Stores the datasource instances containing the renderable data. Used for template rendering.
     *
     * @var TemplateDataSource[]
     */
    private $templateData = [];

    protected abstract function dataSources();

    /**
     * @param array|\stdClass|TemplateDataSourceInterface $data
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

    public function getTemplatePlaceholders()
    {
        $placeholders = [];

        foreach ($this->dataSources() as $dataSource) {
            $placeholders[] = $dataSource->getPlaceholders();
        }

        return $placeholders;
    }

    public function render()
    {
        return $this->renderer->render(
            $this->layout,
            $this->getTemplates(),
            $this->templateData
        );
    }

    public function renderPdf($filePath)
    {
        $renderer = $this->getPdfRenderer();

        $renderer->render(
            $this->layout,
            $this->getTemplates(),
            $this->templateData,
            $filePath
        );

        return $filePath;
    }

    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return PdfRendererInterface
     * @throws InvalidClassException
     */
    private function getPdfRenderer() : PdfRendererInterface
    {
        $pdfRendererClass = config('document_templates.pdf_renderer');
        $renderer = new $pdfRendererClass();

        if (!$renderer instanceof PdfRendererInterface) {
            throw new InvalidClassException(sprintf('The configured pdf renderer (%s) is invalid. The renderer should implement the %s',
                $pdfRendererClass,
                PdfRendererInterface::class));
        }
        return $renderer;
    }
}