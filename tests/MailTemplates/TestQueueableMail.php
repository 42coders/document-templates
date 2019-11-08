<?php


namespace BWF\DocumentTemplates\Tests\MailTemplates;

use App\User;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateFactory;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModel;
use BWF\DocumentTemplates\DocumentTemplates\DocumentTemplateModelInterface;
use BWF\DocumentTemplates\MailTemplates\MailTemplateInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TestQueueableMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @var MailTemplateInterface */
    public $mailTemplate;


    /**
     * Create a new message instance.
     *
     * @param DocumentTemplateModelInterface $mailTemplateModel
     * @param array $templateData
     * @throws \Exception
     */
    public function __construct(DocumentTemplateModelInterface $mailTemplateModel, $templateData = [])
    {
        $this->mailTemplate = DocumentTemplateFactory::build($mailTemplateModel);
        foreach ($templateData as $name => $data){
            $this->mailTemplate->addTemplateData($data, $name);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->html($this->mailTemplate->render())
            ->subject($this->mailTemplate->renderSubject());
    }
}
