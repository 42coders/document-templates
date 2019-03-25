<?php


namespace BWF\DocumentTemplates\Tests\Controllers;


use BWF\DocumentTemplates\Http\Controllers\DocumentTemplatesController;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplate;

class DemoDocumentTemplatesController extends DocumentTemplatesController
{
    protected $documentClasses = [
        DemoDocumentTemplate::class
    ];

}