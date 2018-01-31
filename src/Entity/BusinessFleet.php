<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessFleet
 *
 * @ORM\Table(name="fleet", schema="business")
 * @ORM\Entity(readOnly=true, repositoryClass="BusinessCore\Entity\Repository\BusinessFleetRepository")
 */
class BusinessFleet {

    const DUMMY_FLEET_LIMIT = 100;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=2, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_header", type="text", nullable=false)
     */
    private $invoiceHeader;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getInvoiceHeader() {
        return $this->invoiceHeader;
    }

}
