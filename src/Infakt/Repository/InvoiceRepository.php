<?php

declare(strict_types=1);

namespace Infakt\Repository;

use Infakt\Model\EntityInterface;
use Infakt\Model\Invoice;

enum DownloadInvoiceType: string {
    case original = "original";
    case copy = "copy";
    case original_duplicate = "original_duplicate";
    case copy_duplicate = "copy_duplicate";
    case duplicate = "duplicate";
    case regular = "regular";
    case double_regular = "double_regular";
}

enum DownloadInvoiceLang: string {
    case pl = "pl";
    case en = "en";
    case pe = "pe";
}

class InvoiceRepository extends AbstractObjectRepository
{
    /**
     * Get a next invoice number.
     *
     * @param string $kind
     */
    public function getNextNumber($kind = 'vat'): string
    {
        $kinds = ['final', 'advance', 'margin', 'proforma', 'vat'];

        if (!\in_array($kind, $kinds)) {
            throw new \LogicException('Invalid invoice kind "'.$kind.'"');
        }

        $query = $this->getServiceName().'/next_number.json?kind='.$kind;
        $response = $this->infakt->get($query);

        $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        return (string) $data['next_number'];
    }

    /**
     * Mark an invoice as paid.
     */
    public function markAsPaid(Invoice $invoice, \DateTime $paidDate = null): void
    {
        $query = $this->getServiceName().'/'.$invoice->getId().'/paid.json';
        $this->infakt->post($query);
    }

    /**
     * Download an invoice as a PDF file.
     */

    public function downloadPdf(Invoice $invoice, DownloadInvoiceType $document_type = DownloadInvoiceType::original, DownloadInvoiceLang $lang = DownloadInvoiceLang::pl): string
    {
        $query = $this->getServiceName().'/'.$invoice->getId()."/pdf.pdf?document_type=".$document_type."&lang=".$lang;
        $response = $this->infakt->get($query);

        return $response->getBody()->getContents();
    }
}
