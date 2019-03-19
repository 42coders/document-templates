<?php

namespace BWF\DocumentTemplates\DocumentTemplates;


use BWF\DocumentTemplates\Layouts\LayoutInterface;
use BWF\Renderers\RendererInterface;

interface DocumentTemplateInterface
{
    /**
     * @param LayoutInterface $layout
     * @return void
     */
    public function setLayout(LayoutInterface $layout);

    /**
     * @param \BWF\DocumentTemplates\Renderers\RendererInterface $renderer
     * @return mixed
     */
    public function setRenderer($renderer);

    /**
     * @param array|\Illuminate\Support\Collection|\stdClass $data
     * @param string $name
     * @return void
     */
    public function addTemplateData($data, $name = '');

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