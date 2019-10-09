<?php


namespace BWF\DocumentTemplates\DocumentTemplates;

interface DocumentTemplateModelInterface
{
    /**
     * @return string
     */
    public function getLayoutName();

    /**
     * @return string
     */
    public function getDocumentClass();

    /**
     * @param string[] $names
     * @return mixed
     */
    public function getEditableTemplates($names = null);
}
