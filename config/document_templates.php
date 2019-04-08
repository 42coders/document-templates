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
    ]
];