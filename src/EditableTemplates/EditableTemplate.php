<?php

namespace BWF\DocumentTemplates\EditableTemplates;

use Illuminate\Database\Eloquent\Model;

abstract class EditableTemplate extends Model implements EditableTemplateInterface
{
    protected $table = 'editable_templates';

    protected $guarded = [];

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
        return $this->name ?? $this->attributes['name'];
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
        return $this->content ?? $this->attributes['content'];
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'content' => $this->getContent()
        ];
    }
}