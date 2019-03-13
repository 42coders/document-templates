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
        $this->twig = new Environment($loader, [
            'cache' => dirname($template),
            'debug' => true
        ]);

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

    public function render($templates, $data)
    {
        $extendedTemplate = '{% extends layout %}';

        foreach ($templates as $template) {
            $extendedTemplate .= sprintf(
                '{%% block %1$s %%}%2$s{%% endblock %1$s %%}',
                $template->getName(),
                $template->getContent()
            );
        }

        $loader = new ArrayLoader([
            'extended.html' => $extendedTemplate,
        ]);

        $this->twig->setLoader($loader);
        $data['layout'] = $this->layout;

        return $this->twig->render('extended.html', $data);

    }
}