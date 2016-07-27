<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessFleet
 *
 * @ORM\Table(name="business_fleet", schema="business")
 * @ORM\Entity(readOnly=true)
 */
class BusinessFleet
{
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
}
