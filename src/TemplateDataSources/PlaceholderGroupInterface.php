<?php


namespace BWF\DocumentTemplates\TemplateDataSources;


interface PlaceholderGroupInterface
{
    public function getName();
    public function getPlaceholders();
}