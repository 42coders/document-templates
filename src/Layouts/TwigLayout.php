<?php

namespace BWF\DocumentTemplates\Layouts;

use BWF\DocumentTemplates\EditableTemplates\HtmlTemplate;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigLayout extends Layout implements LayoutInterface
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var \Twig\Template
     */
    protected $template;

    public function load($template)
    {
        parent::load($template);

        $loader = new FilesystemLoader(dirname($template));
        $this->twig = new Environment($loader, [
            'cache' => dirname($template),
        ]);

        $this->template = $this->twig->loadTemplate(basename($template));
    }

    public function getTemplates()
    {
        parent::getTemplates();

        $context = $this->template->getSourceContext();
        $blocks = $this->template->getBlockNames([$context]);

        $templates = [];

        foreach ($blocks as $block) {
            $templates[] = new HtmlTemplate($block);
        }

        return $templates;
    }
}