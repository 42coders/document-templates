<?php

namespace BWF\DocumentTemplates\Tests;

use BWF\DocumentTemplates\DocumentTemplatesServiceProvider;

/**
 * Class TestCase base Class for test cases
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $documentTemplatesConfig = include __DIR__ . '/../config/document_templates.php';
        config( ['document_templates' => $documentTemplatesConfig]);
        config(['document_templates.layout_path' => __DIR__ . '/Stubs/']);

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->artisan('migrate', ['--database' => 'testing'])->run();

    }

    protected function getPackageProviders($app)
    {
        return [
            DocumentTemplatesServiceProvider::class
        ];
    }

    public function skipOnTravis()
    {
        if (! empty(getenv('TRAVIS_BUILD_ID'))) {
            $this->markTestSkipped('Skipping because this test does not run properly on Travis');
        }
    }
}
