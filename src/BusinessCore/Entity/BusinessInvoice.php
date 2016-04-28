<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessInvoice
 *
 * @ORM\Table(name="business_invoice", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessInvoiceRepository")

 */
class BusinessInvoice
{
    /**
     * @var Invoice
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Invoice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="invoice_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $invoice;

    /**
     * @var Business
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     * })
     */
    private $business;

    /**
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }
}
