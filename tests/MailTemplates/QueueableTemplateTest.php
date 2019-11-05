<?php

namespace BWF\DocumentTemplates\Tests\MailTemplates;


use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplate;
use BWF\DocumentTemplates\Tests\DocumentTemplates\DemoDocumentTemplateModel;
use BWF\DocumentTemplates\Tests\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

class QueueableTemplateTest extends TestCase
{
    /**
     * This function should only be used for generating the text file with the serialized object
     *
     *
     * @throws \Exception
     *
     */
    private function serialize()
    {
        $documentTemplateData = [
            'name' => '',
            'document_class' => DemoMailTemplate::class,
            'layout' => 'TestLayout.html.twig',
            'subject' => 'Hello {{user.name}}'
        ];

        $documentTemplateModel = new DemoDocumentTemplateModel();
        $documentTemplateModel->fill($documentTemplateData);

        $user = new User();
        $user->email = 'test@email.com';
        $user->name = 'Test User';

        $mailable = new TestQueueableMail($documentTemplateModel, ['user' => $user]);

        $mailable = unserialize(serialize($mailable));

        if($f = @fopen(__DIR__ . '/serialized.txt',"w")) {
            if (@fwrite($f, serialize($mailable))) {
                @fclose($f);
            }
        }
    }


    /**
     * This test should be run on the unserialised object read from the text file. This simulates the Laravel queue
     * behaviour, the serialize method shouldn't be called in the same thread as the unserialize, it has to be asynchronous
     * in order to test the queue behaviour!
     */
    public function testShouldRenderAfterSerializeAndUnserialize()
    {
        $this->skipOnTravis();

        $mailable = unserialize(file_get_contents(__DIR__ . '/serialized.txt'));
        $this->assertEquals('Hello Test User', $mailable->build()->subject);

    }

}
