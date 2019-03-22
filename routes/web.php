<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('document-templates', 'BWF\DocumentTemplates\Http\Controllers\DocumentTemplatesController', [
    'names' => [
        'index' => 'document-templates.index',
        'create' => 'document-templates.create',
        'store' => 'document-templates.store',
        'edit' => 'document-templates.edit',
        'update' => 'document-templates.update',
        'destroy' => 'document-templates.destroy',
        'show' => 'document-templates.show',
    ]
]);
