<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Invoice;
use Knp\Snappy\Pdf;
use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

class InvoicePdfService
{
    /**
     * @var PhpRenderer
     */
    private $viewRenderer;
    /**
     * @var Pdf
     */
    private $pdfService;

    /**
     * InvoicePdfService constructor.
     * @param PhpRenderer $viewRenderer
     * @param Pdf $pdfService
     */
    public function __construct(
        PhpRenderer $viewRenderer,
        Pdf $pdfService
    ) {
        $this->viewRenderer = $viewRenderer;
        $this->pdfService = $pdfService;
    }

    public function generatePdfFromInvoice(Invoice $invoice)
    {
        $this->pdfService->setOptions([
            'footer-right' => '[page]/[topage]',
            'footer-left' => 'Share \'N Go',
            'footer-font-name' => 'Arial Sans Serif',
            'footer-font-size' => '10',
            'footer-line' => true,
            'lowquality' => false,
            'image-quality' => 100
        ]);

        $layoutViewModel = new ViewModel();
        $layoutViewModel->setTemplate('pdf/layout-pdf');

        $viewModel = new ViewModel([
            'invoiceNumber' => $invoice->getInvoiceNumber(),
            'invoiceContent' => $invoice->getContent()
        ]);

        $templateVersion = $invoice->getContent()['template_version'];
        $viewModel->setTemplate('pdf/invoice-pdf-v' . $templateVersion);

        $layoutViewModel->setVariables([
            'content' => $this->viewRenderer->render($viewModel),
            'templateVersion' => $templateVersion
        ]);

        $htmlOutput = $this->viewRenderer->render($layoutViewModel);

        $output = $this->pdfService->getOutputFromHtml($htmlOutput);
        $response = new Response();
        $headers  = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/pdf');
        $headers->addHeaderLine(
            'Content-Disposition',
            "attachment; filename=\"Fattura-" . $invoice->getInvoiceNumber() . ".pdf\""
        );
        $headers->addHeaderLine('Content-Length', strlen($output));

        $response->setContent($output);

        return $response;
    }
}