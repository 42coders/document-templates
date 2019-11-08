<?php

namespace BWF\DocumentTemplates\Tests\MailTemplates;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\Tests\TestCase;
use Twig\Sandbox\SecurityNotAllowedTagError;

class MailTemplateTest extends TestCase
{

    public function testRenderSubject()
    {
        $documentTemplateModel = new DocumentTemplateModel();
        $documentTemplateModel->subject = 'Subject {{user.email}}';

        $newsletterTemplate = new DemoMailTemplate();
        $newsletterTemplate->init($documentTemplateModel);

        $user = [
            'email' => 'User Email'
        ];

        $newsletterTemplate->addTemplateData($user, 'user');
        $rendered = $newsletterTemplate->renderSubject();

        $this->assertEquals('Subject User Email', $rendered);


        $this->expectException(SecurityNotAllowedTagError::class);
        $documentTemplateModel->subject = 'Subject {% if user.email %}{% endif %}';
        $newsletterTemplate->renderSubject();
    }
}
