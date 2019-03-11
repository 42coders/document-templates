<?php

namespace BWF\DocumentTemplates;

use BWF\Layouts\LayoutInterface;
use BWF\TemplateDataSources\TemplateDataSourceInterface;

interface DocumentTemplateInterface
{
    /**
     * @param LayoutInterface $layout
     * @return void
     */
    public function setLayout(LayoutInterface $layout);

    /**
     * @return mixed
     */
    public function store();

    /**
     * @param TemplateDataSourceInterface $data
     * @return void
     */
    public function addTemplateData(TemplateDataSourceInterface $data);

    /**
     * @param TemplateDataSourceInterface[] $data
     * @return void
     */
    public function setTemplateData($data);

    /**
     * @return string|boolean
     */
    public function render();
}