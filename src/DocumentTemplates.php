<?php


namespace BWF\DocumentTemplates;


use Illuminate\Support\Facades\Route;

class DocumentTemplate
{
    public static function routes($controller, $uri = 'document-templates')
    {
        Route::resource($uri, $controller, [
            'names' => [
                'index' => $uri . '.index',
                'create' => $uri . '.create',
                'store' => $uri . '.store',
                'edit' => $uri . '.edit',
                'update' => $uri . '.update',
                'destroy' => $uri . '.destroy',
                'show' => $uri . '.show',
            ]
        ]);
    }
}