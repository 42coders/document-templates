<?php


namespace BWF\DocumentTemplates\Http\Responses;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface;
use Illuminate\Support\Collection;

class DocumentTemplateResponse
{
    public $status = false;
    public $documentTemplate = null;
    public $documentClasses ;
    public $placeholders ;
    public $templates;
    public $redirect;

    /**
     * DocumentTemplateResponse constructor.
     * @param bool $status
     * @param DocumentTemplateModelInterface $documentTemplate
     * @param Collection $documentClasses
     * @param Collection $placeholders
     * @param Collection $templates
     * @param string $redirect
     */
    public function __construct(
        $status,
        DocumentTemplateModelInterface $documentTemplate = null,
        $documentClasses = null,
        $placeholders = null,
        $templates = null,
        $redirect = ''
    ) {
        $this->status = $status;
        $this->documentTemplate = $documentTemplate;
        $this->documentClasses = $documentClasses;
        $this->placeholders = $placeholders;
        $this->templates = $templates;
        $this->redirect = $redirect;
    }
}
