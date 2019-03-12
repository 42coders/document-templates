<?php
/**
 * Created by PhpStorm.
 * User: vernerd
 * Date: 2019-03-12
 * Time: 10:23
 */

namespace BWF\DocumentTemplates\EditableTemplates;


abstract class EditableTemplate implements EditableTemplateInterface
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}