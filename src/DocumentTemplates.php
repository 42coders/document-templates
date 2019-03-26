<?php


namespace BWF\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use Illuminate\Support\Facades\Route;

class DocumentTemplates
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

        Route::post($uri . '/templates/{documentTemplate?}', $controller . '@templates');
        Route::post($uri . '/placeholders', $controller . '@placeholders');

        Route::model('documentTemplate', DocumentTemplateModel::class);
    }
}