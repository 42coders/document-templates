<?php

namespace BWF\DocumentTemplates\EditableTemplates;

use Illuminate\Database\Eloquent\Model;

abstract class EditableTemplate extends Model implements EditableTemplateInterface
{
    /**
     * @var string $content
     */
    protected $content;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

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