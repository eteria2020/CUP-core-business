<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\Hostname;

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
     * @ORM\ManyToOne(targetEntity="Business")
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
}
