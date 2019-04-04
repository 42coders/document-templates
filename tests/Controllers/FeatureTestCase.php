<?php

namespace BWF\DocumentTemplates\Tests\Controllers;

use BWF\DocumentTemplates\DocumentTemplates;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Support\Facades\View;

/**
 * Class TestCase base Class for test cases
 */
abstract class FeatureTestCase extends \Orchestra\Testbench\BrowserKit\TestCase
{
    use InteractsWithConsole;
    use InteractsWithDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['document_templates.layout_path' => __DIR__ . '/../Stubs/']);

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->artisan('migrate', ['--database' => 'testing'])->run();

        DocumentTemplates::routes(DemoDocumentTemplatesController::class);

        View::addNamespace('document-templates', __DIR__.'/../../resources/views');
        View::addLocation(__DIR__.'/../../resources/views');

    }
}
