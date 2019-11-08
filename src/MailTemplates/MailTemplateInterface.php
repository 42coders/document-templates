<?php

namespace BWF\DocumentTemplates\MailTemplates;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateInterface;

interface MailTemplateInterface extends DocumentTemplateInterface
{

    /**
     * @return string
     */
    public function renderSubject();
}
