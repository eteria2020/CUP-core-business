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
    const STATUS_DELETED = 'deleted';
    const STATUS_APPROVED_WAITING_BUSINESS_ENABLING = 'approved_waiting_for_business_enabling';

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
        $this->insertedTs = date_create();
        if ($this->isEmailControlEnabledAndEmailApproved($employee, $business)) {
            $status = $business->isEnabled() ? self::STATUS_APPROVED : self::STATUS_APPROVED_WAITING_BUSINESS_ENABLING;
            $this->status = $status;
            $this->confirmedTs = date_create();
        } else {
            $this->status = self::STATUS_PENDING;
        }
    }

    private function isEmailControlEnabledAndEmailApproved(Employee $employee, Business $business)
    {
        if ($business->isBusinessMailControlEnabled()) {
            $employeeEmailDomain = substr(strrchr($employee->getEmail(), "@"), 1);

            foreach ($business->getDomains() as $domain) {
                if ($employeeEmailDomain == $domain) {
                    return true;
                }
            }
        }
        return false;
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
    public function isApprovedWaitingForBusinessApproval()
    {
        return $this->status == self::STATUS_APPROVED_WAITING_BUSINESS_ENABLING;
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
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETED;
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

    public function assignToGroup(Group $group)
    {
        $this->group = $group;
    }

    public function removeGroup()
    {
        $this->group = null;
    }
}
