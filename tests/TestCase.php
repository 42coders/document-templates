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
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->artisan('migrate', ['--database' => 'testing'])->run();

    }
}
