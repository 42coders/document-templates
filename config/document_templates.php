<?php

return [

    /**
     * The base path to search for the layout twig files
     */
    'layout_path' => resource_path('templates'),

    /**
     * Twig sandbox configuration
     *
     * @see https://twig.symfony.com/doc/2.x/api.html#sandbox-extension
     */
    'template_sandbox' => [
        'allowedTags' => ['for'],
        'allowedFilters' => ['escape'],
        'allowedMethods' => [],
        'allowedProperties' => ['*'],
        'allowedFunctions' => []
    ],
    /**
     * Twig environment configuration
     *
     * @see https://twig.symfony.com/doc/2.x/api.html#environment-options
     */
    'twig' => [
        'environment' => [
            'debug' => false,
            'charset' => 'utf-8',
            'base_template_class' => '\Twig\Template',
            'cache' => false,
            'auto_reload' => false,
            'strict_variables' => false,
            'autoescape' => false,
            'optimizations' => -1
        ],
        'extensions' => [],
    ],

    /**
     * The model class to be used with route model binding, and in the DocumentTemplatesController
     */
    'model_class' => \BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel::class,

    /**
     * Base url to use for generating the routes with DocumentTemplate::routes() (e.g /document-templates/, /document-templates/1/edit).
     * These routes are also named by this base url, and they look like this: route('document-template.index')
     */
    'base_url' => 'document-templates',

    /**
     * Configure the pdf renderer to use with the application
     */
    'pdf_renderer' => \BWF\DocumentTemplates\Renderers\DomPdfRenderer::class,

    /**
     * Configure if the package should load it's default routes
     */
    'load_default_routes' => false
];