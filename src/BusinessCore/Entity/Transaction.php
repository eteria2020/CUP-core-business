<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction", schema="business")
 * @ORM\Entity()

 */
class Transaction
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.transaction_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="text", nullable=false)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="outcome", type="text", nullable=true)
     */
    private $outcome;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_ts", type="datetime", nullable=false)
     */
    private $createdTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="outcome_ts", type="datetime", nullable=true)
     */
    private $outcomeTs;

    /**
     * @ORM\ManyToMany(targetEntity="BusinessPayment", mappedBy="transactions")
     */
    private $payments;

    /**
     * Transaction constructor.
     */
    public function __construct()
    {
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
