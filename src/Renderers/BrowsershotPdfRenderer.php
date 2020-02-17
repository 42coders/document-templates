<?php


namespace BWF\DocumentTemplates\Renderers;


use BWF\DocumentTemplates\Layouts\LayoutInterface;
use Spatie\Browsershot\Browsershot;

class BrowsershotPdfRenderer extends PdfRenderer
{
    public function render(LayoutInterface $layout, $templates, $data, $filePath)
    {
        Browsershot::html($layout->render($templates, $data))->savePdf($filePath);
        return $filePath;
    }

}