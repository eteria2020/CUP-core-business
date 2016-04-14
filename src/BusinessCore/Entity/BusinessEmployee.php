<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\Hostname;

/**
 * BusinessEmployee
 *
 * @ORM\Table(name="business_employee", schema="businesses")
 * @ORM\Entity

 */
class BusinessEmployee
{

    /**
     * @var Employee
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Employee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employee_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $employee;

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
     * @var boolean
     *
     * @ORM\Column(name="blocked", type="boolean", nullable=false)
     */
    private $blocked;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted_ts", type="datetime", nullable=false)
     */
    private $insertedTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirmed_ts", type="datetime")
     */
    private $confirmedTs;


    public function __construct($employee, $business)
    {
        $this->employee = $employee;
        $this->business = $business;
    }

    /**
     * @return Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return boolean
     */
    public function isBlocked()
    {
        return $this->blocked;
    }
}
