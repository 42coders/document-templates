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

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);
}
