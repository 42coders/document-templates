<?php

namespace BWF\DocumentTemplates\EditableTemplates;

use Illuminate\Database\Eloquent\Model;

abstract class EditableTemplate extends Model implements EditableTemplateInterface
{
    protected $table = 'editable_templates';

    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->attributes['name'] ?? '';
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->attributes['name'] = $name;
    }

    public function getContent()
    {
        return $this->attributes['content'] ?? '';
    }

    public function setContent($content)
    {
        $this->attributes['content'] = $content;
    }
}