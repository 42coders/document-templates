<?php


namespace BWF\DocumentTemplates;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplateModel;
use Illuminate\Support\Facades\Route;

class DocumentTemplates
{
    public static function routes($controller)
    {
        $uri = config('document_templates.base_url');

        Route::middleware(['bindings'])->group(function () use ($uri, $controller) {
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
            ])->parameters([
                $uri => 'document_template'
            ]);
            ;

            Route::post($uri . '/templates/{document_template?}', $controller . '@templates')->name($uri . '.templates');
            Route::post($uri . '/placeholders/{document_template?}', $controller . '@placeholders')->name($uri . '.placeholders');

            Route::model('document_template', config('document_templates.model_class'));
        });
    }
}
