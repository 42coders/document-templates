<?php

namespace BWF\DocumentTemplates\EditableTemplates;

use Illuminate\Database\Eloquent\Model;

abstract class EditableTemplate extends Model implements EditableTemplateInterface
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

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