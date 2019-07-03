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
        ]
    ],

    /**
     * The model class to be used with route model binding, and in the controller
     */
    'model_class' => \BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel::class,

    /**
     * Base url to use by the generate routes (e.g /document-templates/, /document-templates/1/edit).
     * The routes are also named by this base url, and they could be used like: route('document.template.index')
     */
    'base_url' => 'document-templates'
];