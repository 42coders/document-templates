<?php

namespace BWF\DocumentTemplates\Tests\Support;

use BWF\DocumentTemplates\Support\TwigDumper;
use PHPUnit\Framework\TestCase;

class TwigDumperTest extends TestCase
{
    protected $dumper;

    protected function setUp(): void
    {
        parent::setUp();
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
}
