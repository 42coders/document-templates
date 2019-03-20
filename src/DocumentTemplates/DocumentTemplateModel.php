<?php


namespace BWF\DocumentTemplates\DocumentTemplates;


use Illuminate\Database\Eloquent\Model;

abstract class DocumentTemplateModel extends Model implements DocumentTemplateModelInterface
{

    protected $table = 'document_templates';

    protected $guarded = [];

    public function editableTemplates()
    {
        return $this->hasMany('\BWF\DocumentTemplates\EditableTemplates\HtmlTemplate', 'document_template_id');
    }

    public function getLayoutName()
    {
        return $this->layout;
    }

    public function getEditableTemplates()
    {
        return $this->editableTemplates;
    }
}