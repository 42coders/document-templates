<?php


namespace BWF\DocumentTemplates;


use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use Illuminate\Support\Facades\Route;

class DocumentTemplates
{
    public static function routes($controller, $uri = 'document-templates')
    {
        Route::middleware(['bindings'])->group(function () use($uri, $controller) {
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
            Route::post($uri . '/placeholders/{documentTemplate?}', $controller . '@placeholders');

            Route::model('documentTemplate', DocumentTemplateModel::class);
        });
    }
}