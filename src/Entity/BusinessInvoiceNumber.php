<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessInvoiceNumber
 *
 * @ORM\Table(name="business_invoice_number", schema="business")
 * @ORM\Entity()

 */
class BusinessInvoiceNumber
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.business_invoice_number_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var BusinessFleet
     *
     * @ORM\ManyToOne(targetEntity="BusinessFleet")
     * @ORM\JoinColumn(name="fleet_id", referencedColumnName="id", nullable=false)
     */
    private $fleet;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer", nullable=false)
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer", nullable=false)
     */
    private $number;
}
