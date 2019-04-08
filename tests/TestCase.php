<?php

namespace BWF\DocumentTemplates\Tests;

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
}
