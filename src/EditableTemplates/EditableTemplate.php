<?php

namespace BWF\DocumentTemplates\EditableTemplates;

use Illuminate\Database\Eloquent\Model;

class EditableTemplate extends Model implements EditableTemplateInterface
{
    protected $table = 'editable_templates';

    protected $guarded = [];

    public function scopeForNames($query, $names)
    {
        return $query->whereIn('name',  $names);
    }

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