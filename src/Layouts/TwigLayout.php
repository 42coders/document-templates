<?php

namespace BWF\DocumentTemplates\Layouts;

use BWF\DocumentTemplates\EditableTemplates\HtmlTemplate;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\FilesystemLoader;

class TwigLayout extends Layout implements LayoutInterface
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var \Twig\TemplateWrapper
     */
    protected $layout;

    public function load($template)
    {
        parent::load($template);

        $loader = new FilesystemLoader(dirname($template));
        $this->twig = new Environment($loader);

        $this->layout = $this->twig->load(basename($template));
    }

    public function getTemplates()
    {
        parent::getTemplates();

        $context = $this->layout->getSourceContext();
        $blocks = $this->layout->getBlockNames([$context]);

        $templates = [];

        foreach ($blocks as $block) {
            $templates[] = new HtmlTemplate($block);
        }

        return $templates;
    }

    /**
     * Generates a new template by extending the layout with the given templates
     *
     * @param array $templates
     * @return string
     */
    protected function extendLayout($templates)
    {
        $extendedTemplate = '{% extends layout %}';

        foreach ($templates as $template) {
            $extendedTemplate .= sprintf(
                '{%% block %1$s %%}%2$s{%% endblock %1$s %%}',
                $template->getName(),
                $template->getContent()
            );
        }

        return $extendedTemplate;
    }

    /**
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface[] $dataSources
     * @return array
     */
    protected function generateTemplateData($dataSources)
    {
        $templateData = [];

        foreach ($dataSources as $dataSource) {
            $templateData = array_merge($templateData, $dataSource->getTemplateData());
        }

        $templateData['layout'] = $this->layout;

        return $templateData;
    }

    /**
     * @param \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[] $templates
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface[] $dataSourcesSources
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render($templates, $dataSourcesSources)
    {
        $loader = new ArrayLoader([
            'extendedLayout' => $this->extendLayout($templates),
        ]);

        $this->twig->setLoader($loader);
        $templateData = $this->generateTemplateData($dataSourcesSources);

        return $this->twig->render('extendedLayout', $templateData);

    }
}