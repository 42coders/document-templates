<?php


namespace BWF\DocumentTemplates\DocumentTemplates;

use Illuminate\Database\Eloquent\Model;

class DocumentTemplateModel extends Model implements DocumentTemplateModelInterface
{

    protected $table = 'document_templates';

    protected $guarded = [];

    public function scopeByDocumentClass($query, $documentClass)
    {
        return $query->where('document_class', $documentClass);
    }

    public function editableTemplates()
    {
        return $this->hasMany('\BWF\DocumentTemplates\EditableTemplates\EditableTemplate', 'document_template_id');
    }

    public function getLayoutName()
    {
        return $this->layout;
    }

    public function getDocumentClass()
    {
        return $this->document_class;
    }

    public function getEditableTemplates($names = null)
    {
        return $this->editableTemplates()->forNames($names)->get();
    }
}
