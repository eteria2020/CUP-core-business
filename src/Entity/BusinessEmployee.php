<?php

namespace BusinessCore\Entity;

use BusinessCore\Helper\EmployeeLimits;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="businessEmployee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employee_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $employee;

    /**
     * @var Business
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="businessEmployee")
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
     * @ORM\Column(name="inserted_ts", type="datetimetz", nullable=true)
     */
    private $insertedTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirmed_ts", type="datetimetz", nullable=true)
     */
    private $confirmedTs;

    /**
     * @var string
     *
     * @ORM\Column(name="time_limits", type="text", nullable=true)
     */
    private $timeLimits;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="businessEmployees")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    public function __construct(Employee $employee, Business $business)
    {
        $this->employee = $employee;
        $this->business = $business;
        $this->insertedTs = date_create();
        $this->timeLimits = 'mo()tu()we()th()fr()sa()su()'; //allow all days by default
        if ($business->canApproveAutomatically($employee)) {
            if ($business->isEnabled()) {
                $this->approve();
            } else {
                $this->approveWaitingForBusinessEnabling();
            }
        } else {
            $this->status = self::STATUS_PENDING;
        }
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
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * 
     * @return string
     */
    public function getGroupName()
    {
        if ($this->group instanceof Group) {
            return $this->group->getName();
        }
        return '-';
    }

    /**
     * 
     * @param \BusinessCore\Entity\Group $group
     */
    public function assignToGroup(Group $group)
    {
        $this->group = $group;
    }

    public function removeGroup()
    {
        $this->group = null;
    }

    public function approveWaitingForBusinessEnabling()
    {
        $this->status = self::STATUS_APPROVED_WAITING_BUSINESS_ENABLING;
        $this->confirmedTs = date_create();
    }

    public function approve()
    {
        $this->status = self::STATUS_APPROVED;
        $this->confirmedTs = date_create();
    }

    public function block()
    {
        $this->status = self::STATUS_BLOCKED;
    }

    public function delete()
    {
        $this->status = self::STATUS_DELETED;
    }

    /**
     * @return string
     */
    public function getReadableInsertDate()
    {
        return $this->getReadableDate($this->insertedTs);
    }

    /**
     * @return string
     */
    public function getReadableConfirmedDate()
    {
        return $this->getReadableDate($this->confirmedTs);
    }

    /**
     * 
     * @param \DateTime $date
     * @return string
     */
    private function getReadableDate($date)
    {
        if ($date instanceof \DateTime) {
            return $date->format('d-m-Y H:i:s');
        }
        return '-';
    }

    /**
     * 
     * @param EmployeeLimits $limits
     */
    public function setLimits(EmployeeLimits $limits)
    {
        $this->timeLimits = $limits->toString();
    }

    /**
     * @return string
     */
    public function getTimeLimits()
    {
        return $this->timeLimits;
    }

    /**
     * 
     * @return boolean
     */
    public function isActive()
    {
        return $this->isPending() ||
        $this->isBlocked() ||
        $this->isApprovedWaitingForBusinessApproval() ||
        $this->isApproved();
    }

    /**
     * 
     * @return DateTime
     */
    public function getInsertedTs(){
        return $this->insertedTs;
    }

    /**
     * 
     * @return DateTime
     */
    public function getConfirmedTs(){
        return $this->confirmedTs;
    }
}
