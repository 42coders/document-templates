<?php

namespace BWF\DocumentTemplates\Tests\Support;

use BWF\DocumentTemplates\Support\TwigDumper;
use BWF\DocumentTemplates\TemplateDataSources\TemplateDataSource;
use BWF\DocumentTemplates\Tests\Layouts\TestTwigExtension;
use BWF\DocumentTemplates\Tests\TestCase;
use Twig\Extension\CoreExtension;

class TwigDumperTest extends TestCase
{
    protected $dumper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpConfig();
        $this->dumper  = new TwigDumper();
    }

    public function test_it_can_dump_integer_variable()
    {
        $result = $this->dumper->dump('This is a {{simpleVariable}}.', ['simpleVariable' => 1]);
        $this->assertEquals(1, $result['simpleVariable']);
    }

    public function test_it_can_dump_date_variable()
    {
        $date = new \DateTime();
        $result = $this->dumper->dump('This is a {{dateVariable}}.', ['dateVariable' => $date]);
        $this->assertEquals($date, $result['dateVariable']);
    }

    public function test_it_can_dump_a_nested_variable()
    {
        $result = $this->dumper->dump('This is a {{object.simpleVariable}}.', ['object' => ['simpleVariable' => 1]]);
        $this->assertEquals(1, $result['object.simpleVariable']);
    }

    public function test_it_can_dump_a_variable_from_if()
    {
        $result = $this->dumper->dump('This is a {% if simpleVariable %} {% endif %}.', ['simpleVariable' => 1]);
        $this->assertEquals(1, $result['simpleVariable']);
    }

    public function test_it_can_dump_a_variable_from_for()
    {
        $result = $this->dumper->dump('This is a {% for variable in variables %} {% endfor %}.', ['variables' => [1, 2, 3]]);
        $this->assertEquals([1, 2, 3], $result['variables']);
    }

    public function test_it_can_dump_functions()
    {
        $result = $this->dumper->dump('This is a {{return_twig}} {{simpleVariable}}.', ['simpleVariable' => 1]);
        $this->assertArrayHasKey('return_twig', $result);
        $this->assertEquals(1, $result['simpleVariable']);
    }

    public function test_it_can_dump_functions_with_placeholders()
    {
        $templateText = 'This is a {{return_twig(\'text with placeholder{{functionPlaceholder}}\')}} {{simpleVariable}}.';

        $result = $this->dumper->dump(
            $templateText,
            [
                'simpleVariable' => 1,
                'functionPlaceholder' => 2
            ]);

        $this->assertArrayHasKey('return_twig(text with placeholder{{functionPlaceholder}})', $result);
        $this->assertEquals(1, $result['simpleVariable']);
        $this->assertEquals(2, $result['functionPlaceholder']);
    }

    protected function setUpConfig(): void
    {
        config([
            'document_templates.twig.extensions' => [
                TestTwigExtension::class,
            ],
            'document_templates.template_sandbox.allowedFunctions' => [
                'return_twig'
            ]
        ]);
    }
}
