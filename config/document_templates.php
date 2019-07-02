<?php

return [

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
    ]
];