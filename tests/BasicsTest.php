<?php

namespace BWF\DocumentTemplates\Tests;

use BWF\DocumentTemplates\DocumentTemplatesServiceProvider;

class BasicsTest extends TestCase
{
    public function testIfThePhpUnitRuns()
    {
        $this->assertTrue(true);
    }

    public function testIfTheServiceProviderBoots()
    {
        $serviceProvider = new DocumentTemplatesServiceProvider(app());
        $serviceProvider->boot();

        $this->assertInstanceOf(DocumentTemplatesServiceProvider::class, $serviceProvider);
    }

    public function testIfTheServiceProviderBootsWithLoadsDefaultRoutes()
    {
        config(['document_templates.load_default_routes' => 'true']);
        $serviceProvider = new DocumentTemplatesServiceProvider(app());
        $serviceProvider->boot();

        $this->assertInstanceOf(DocumentTemplatesServiceProvider::class, $serviceProvider);
    }
}
