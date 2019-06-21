<?php

namespace BWF\DocumentTemplates\Tests\MailTemplates;

use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\EditableTemplates\EditableTemplate;
use BWF\DocumentTemplates\MailTemplates\TemplateMailable;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplate;
use BWF\DocumentTemplates\Tests\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class TemplateMailableTest extends TestCase
{

    protected function createMailTemplate()
    {
        $documentTemplateData = [
            'name' => 'test',
            'subject' => 'Test mail',
            'document_class' => DemoMailTemplate::class,
            'layout' => 'TestIterableDataSource.html.twig'
        ];

        $documentTemplateModel = new DocumentTemplateModel();
        $documentTemplateModel->fill($documentTemplateData);
        $documentTemplateModel->save();
        $content = [
            "name" => "content",
            "content" => "<p>content</p>"
        ];
        $documentTemplateModel->editableTemplates()->save(new EditableTemplate($content));

        return $documentTemplateModel;
    }

    public function testSendNewsletter()
    {
        Mail::fake();

        $template = $this->createMailTemplate();
        $user = new User();

        $user->email = 'test@email.com';

        Mail::to($user)->send(new TemplateMailable($template, ['user' => $user]));

        Mail::assertSent(TemplateMailable::class, function (Mailable $mail) use ($user, $template) {
            $mail->build();

            return $mail->hasTo($user->email)
                && $mail->subject === $template->subject;
        });
    }
}
