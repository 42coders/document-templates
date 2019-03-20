<?php

namespace BWF\DocumentTemplates\EditableTemplates;

interface EditableTemplateInterface
{
    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @param string $content
     * @return mixed
     */
    public function setContent($content);
}