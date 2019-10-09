<?php


namespace BWF\DocumentTemplates\Renderers;

use BWF\DocumentTemplates\Layouts\LayoutInterface;
use Illuminate\Support\Facades\App;

class DomPdfRenderer extends PdfRenderer
{
    public function render(LayoutInterface $layout, $templates, $data, $filePath)
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($layout->render($templates, $data));
        $pdf->save($filePath);

        return $filePath;
    }
}
