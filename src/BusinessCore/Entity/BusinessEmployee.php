<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\Hostname;

/**
 * BusinessEmployee
 *
 * @ORM\Table(name="business_employee", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessEmployeeRepository")

 */
class BusinessEmployee
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_BLOCKED = 'blocked';

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
     * @var string
     *
     * @ORM\Column(name="status", type="text", nullable=false)
     */
    private $status;

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

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;


    public function __construct(Employee $employee, Business $business)
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
        return $this->status == self::STATUS_BLOCKED;
    }

    /**
     * @return boolean
     */
    public function isApproved()
    {
        return $this->status == self::STATUS_APPROVED;
    }

    /**
     * @return boolean
     */
    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    public function getGroupName()
    {
        if ($this->group instanceof Group) {
            return $this->group->getName();
        }
        return '-';
    }

    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    public function removeGroup()
    {
        $this->group = null;
    }
}
