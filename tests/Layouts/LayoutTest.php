<?php

namespace BWF\DocumentTemplates\Tests\Layouts;


use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\Layouts\Layout;
use BWF\DocumentTemplates\Layouts\TwigLayout;
use BWF\DocumentTemplates\Tests\TestCase;

class LayoutTest extends TestCase
{
    public function testGetTemplates()
    {
        $layout = new TwigLayout();
        $this->assertInstanceOf(Layout::class, $layout);

        $layout->load(__DIR__ . '/TestLayout.html.twig');

        $templates = $layout->getTemplates();
        $this->assertNotEmpty($templates);

        foreach ($templates as $template){
            $this->assertInstanceOf(EditableTemplate::class, $template);
        }
    }

}
