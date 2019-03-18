<?php

namespace BWF\DocumentTemplates\DocumentTemplates;


use BWF\DocumentTemplates\Layouts\LayoutInterface;

interface DocumentTemplateInterface
{
    /**
     * @param LayoutInterface $layout
     * @return void
     */
    public function setLayout(LayoutInterface $layout);

    /**
     * @param array|\Illuminate\Support\Collection|\stdClass $data
     * @param string $name
     * @return void
     */
    public function addTemplateData($data, $name = '');

    /**
     * @param TemplateDataSourceInterface[] $data
     * @return void
     */
    public function setTemplateData($data);

    /**
     * @return string[]
     */
    public function getTemplatePlaceholders();

    /**
     * @return string|boolean
     */
    public function render();

    /**
     * @return mixed
     */
    public function store();
}