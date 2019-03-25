<?php


namespace BWF\DocumentTemplates\Tests\Controllers;


use BWF\DocumentTemplates\Http\Controllers\DocumentTemplatesController;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplate;
use BWF\DocumentTemplates\Tests\Stubs\IterableTemplateData;

class DemoDocumentTemplatesController extends DocumentTemplatesController
{
    use IterableTemplateData;

    protected $documentClasses = [
        DemoDocumentTemplate::class
    ];

}