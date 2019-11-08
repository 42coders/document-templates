<?php


namespace BWF\DocumentTemplates\Tests\MailTemplates;


use BWF\DocumentTemplates\MailTemplates\MailTemplate;
use BWF\DocumentTemplates\MailTemplates\MailTemplateInterface;

class DemoMailTemplate implements MailTemplateInterface
{
    use MailTemplate;

    protected function dataSources()
    {
        $user = [
            'email' => ''
        ];

        return [
            $this->dataSource($user, 'user' )
        ];
    }

}