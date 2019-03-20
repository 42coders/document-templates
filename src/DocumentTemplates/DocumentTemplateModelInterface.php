<?php


namespace BWF\DocumentTemplates\DocumentTemplates;


interface DocumentTemplateModelInterface
{
    /**
     * @return string
     */
    public function getLayoutName();

    /**
     * @return mixed
     */
    public function getEditableTemplates();
}