<?php


namespace BWF\DocumentTemplates\DocumentTemplates;


use Illuminate\Database\Eloquent\Model;

abstract class DocumentTemplateModel extends Model
{

    protected $table = 'document_templates';

    protected $guarded = [];

    public function editableTemplates()
    {
        return $this->hasMany('\BWF\DocumentTemplates\EditableTemplates\EditableTemplate');
    }

    /**
     * @return array
     */
    protected abstract function getDataToSave();

    public function store()
    {
        $this->fill($this->getDataToSave());

        return $this->save();
    }
}