<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\BusinessInvoice;
use Knp\Snappy\Pdf;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

class PdfService
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
     * PdfService constructor.
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

    public function generatePaymentReport(array $payments)
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
        $layoutViewModel->setTemplate('pdf/layout-report-pdf');

        $viewModel = new ViewModel([
            'payments' => $payments,
        ]);


        $viewModel->setTemplate('pdf/report-pdf');

        $layoutViewModel->setVariables([
            'content' => $this->viewRenderer->render($viewModel)
        ]);

        $htmlOutput = $this->viewRenderer->render($layoutViewModel);

        $output = $this->pdfService->getOutputFromHtml($htmlOutput);
        return $output;
    }

    public function generatePdfFromInvoice(BusinessInvoice $invoice)
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
        return $output;
    }
}
