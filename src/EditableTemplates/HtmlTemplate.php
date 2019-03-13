<?php

namespace BWF\DocumentTemplates\EditableTemplates;

class HtmlTemplate extends EditableTemplate
{
    /**
     * @var string
     */
    protected $content;

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function store()
    {
        // TODO: Implement store() method.
    }

}