<?php


namespace BWF\DocumentTemplates\MailTemplates;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplate;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;

trait MailTemplate
{
    use DocumentTemplate;

    protected function getSubject()
    {
        return $this->model->subject ?? '';
    }

    public function renderSubject()
    {
        $editableTemplate = new EditableTemplate();
        $editableTemplate->setName('subject');
        $editableTemplate->setContent($this->getSubject());

        return $this->layout->renderSingle($editableTemplate, $this->templateData);
    }
}
