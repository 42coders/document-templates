<?php


namespace BWF\DocumentTemplates\DocumentTemplates;


use Illuminate\Database\Eloquent\Model;

abstract class DocumentTemplateModel extends Model
{

    protected $table = 'document_templates';

    public function store(){

    }
}