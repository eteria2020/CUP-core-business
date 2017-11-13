<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table(name="employee_group", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\GroupRepository")

 */
class Group
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.group_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business", inversedBy="businessGroups")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     * })
     */
    private $business;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="daily_limit", type="integer", nullable=true)
     */
    private $dailyMinutesLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="weekly_limit", type="integer", nullable=true)
     */
    private $weeklyMinutesLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="monthly_limit", type="integer", nullable=true)
     */
    private $monthlyMinutesLimit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_ts", type="datetime", nullable=false)
     */
    private $createdTs;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="BusinessEmployee", mappedBy="group")
     */
    private $businessEmployees;

    public function __construct(Business $business, $name, $description)
    {
        $this->business = $business;
        $this->name = $name;
        $this->description = $description;
        $this->createdTs = date_create();
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return BusinessEmployee[]
     */
    public function getBusinessEmployees()
    {
        return $this->businessEmployees;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getDailyMinutesLimit()
    {
        return $this->dailyMinutesLimit;
    }

    /**
     * @return int
     */
    public function getWeeklyMinutesLimit()
    {
        return $this->weeklyMinutesLimit;
    }

    /**
     * @return int
     */
    public function getMonthlyMinutesLimit()
    {
        return $this->monthlyMinutesLimit;
    }

    /**
     * @param int $dailyMinutesLimit
     */
    public function setDailyMinutesLimit($dailyMinutesLimit)
    {
        $this->dailyMinutesLimit = $dailyMinutesLimit;
    }

    /**
     * @param int $weeklyMinutesLimit
     */
    public function setWeeklyMinutesLimit($weeklyMinutesLimit)
    {
        $this->weeklyMinutesLimit = $weeklyMinutesLimit;
    }

    /**
     * @param int $monthlyMinutesLimit
     */
    public function setMonthlyMinutesLimit($monthlyMinutesLimit)
    {
        $this->monthlyMinutesLimit = $monthlyMinutesLimit;
    }
}
