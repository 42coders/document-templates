<?php

namespace BWF\DocumentTemplates\Layouts;

use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplateInterface;
use BWF\DocumentTemplates\EditableTemplates\HtmlTemplate;
use BWF\DocumentTemplates\Exceptions\InvalidTwigExtension;
use BWF\DocumentTemplates\Sandbox\SecurityPolicy;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use \DirectoryIterator;
use \Exception;

class TwigLayout extends Layout implements LayoutInterface
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var SandboxExtension
     */
    protected $sandbox;

    /**
     * @var \Twig\TemplateWrapper
     */
    protected $layout;

    /**
     * @var string Base path for template loading
     */
    protected $basePath;

    /** @var FilesystemLoader */
    protected $fileLoader;

    /**
     * TwigLayout constructor.
     */
    public function __construct()
    {
        $this->basePath = config('document_templates.layout_path');
        $this->createEnvironment();
    }

    /**
     * Creates the twig environment and sets the sets up the security policy.
     */
    private function createEnvironment()
    {
        $this->fileLoader = new FilesystemLoader($this->basePath);
        $this->twig = new Environment($this->fileLoader, config('document_templates.twig.environment'));

        $this->loadExtensions($this->getExtensions());
        $this->loadSandbox();
    }

    /**
     * Gets the set extensions from the config.
     *
     * @return array
     */
    private function getExtensions(): array
    {
        return config('document_templates.twig.extensions', []);
    }

    /**
     * Loads extensions.
     *
     * @param array $extensions
     *
     * @throws InvalidTwigExtension
     */
    private function loadExtensions(array $extensions): void
    {
        foreach ($extensions as $extension) {
            if (!in_array(ExtensionInterface::class, class_implements($extension))) {
                throw new InvalidTwigExtension(
                    $extension.' does not implement the required '.ExtensionInterface::class.' interface.'
                );
            }
            if ($this->twig->hasExtension($extension)) {
                continue;
            }
            $this->twig->addExtension(app($extension));
        }
    }

    /**
     * Sets the sets up the security policy and loads the sandbox.
     */
    private function loadSandbox(): void
    {
        $policy = new SecurityPolicy(
            config('document_templates.template_sandbox.allowedTags'),
            config('document_templates.template_sandbox.allowedFilters'),
            config('document_templates.template_sandbox.allowedMethods'),
            config('document_templates.template_sandbox.allowedProperties'),
            config('document_templates.template_sandbox.allowedFunctions')
        );
        $this->sandbox = new SandboxExtension($policy);
        $this->twig->addExtension($this->sandbox);
    }


    /**
     * @param string $template
     * @return mixed|void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function load($template)
    {
        $this->createEnvironment();
        $templateName = basename($template);
        $this->setName($templateName);
        $this->layout = $this->twig->load($templateName);
    }

    /**
     * return string[]
     */
    public function getAvailableLayouts()
    {
        $files = [];
        $iterator = new DirectoryIterator($this->basePath);
        /** @var \SplFileInfo $fileinfo */
        foreach ($iterator as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->getExtension() === 'twig') {
                $files[] = $fileinfo->getBasename();
            }
        }

        return collect($files);
    }

    public function getTemplates()
    {
        $templates = [];

        if (!$this->layout) {
            throw new Exception('Layout is not loaded!');
        }

        //It is necessary to load the template again, without this, the rendering fails when used with Laravel queues.
        $this->load($this->name);
        $context = $this->layout->getSourceContext();
        $blocks = $this->layout->getBlockNames([$context]);


        foreach ($blocks as $block) {
            $template = new EditableTemplate();
            $template->setName($block);

            $templates[] = $template;
        }

        return collect($templates);
    }

    protected function renderWithSandbox(EditableTemplateInterface $template, $templateData)
    {
        $this->sandbox->enableSandbox();

        $templateName = 'template_' . $template->getName();
        $loader = new ArrayLoader([
            $templateName => $template->getContent()
        ]);
        $this->twig->setLoader($loader);
        $rendered = $this->twig->render($templateName, $templateData);
        $this->twig->setLoader($this->fileLoader);

        $this->sandbox->disableSandbox();

        return $rendered;
    }

    /**
     * Generates a new template by extending the layout with the given templates
     *
     * @param array $templates
     * @param  array $templateData
     * @return string
     */
    protected function extendLayout($templates, $templateData)
    {
        $extendedTemplate = '{% extends layout %}';

        foreach ($templates as $template) {
            $extendedTemplate .= sprintf(
                '{%% block %1$s %%}%2$s{%% endblock %1$s %%}',
                $template->getName(),
                $this->renderWithSandbox($template, $templateData)
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

        return $templateData;
    }

    /**
     * @param \BWF\DocumentTemplates\EditableTemplates\EditableTemplate[] $templates
     * @param \BWF\DocumentTemplates\TemplateDataSources\TemplateDataSourceInterface[] $dataSources
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render($templates, $dataSources)
    {
        $templateData = $this->generateTemplateData($dataSources);
        $templateData['layout'] = $this->layout;

        $loader = new ArrayLoader([
            'extendedLayout' => $this->extendLayout($templates, $templateData),
        ]);

        $chainLoader = new ChainLoader([$this->fileLoader,$loader]);

        $this->twig->setLoader($chainLoader);

        return $this->twig->render('extendedLayout', $templateData);
    }

    public function renderSingle(EditableTemplateInterface $template, $dataSources)
    {
        return $this->renderWithSandbox($template, $this->generateTemplateData($dataSources));
    }
}
