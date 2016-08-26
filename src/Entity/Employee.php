<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Employee
 *
 * @ORM\Table(name="employee", schema="business")
 * @ORM\Entity(readOnly=true, repositoryClass="BusinessCore\Entity\Repository\EmployeeRepository")
 */

class Employee
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
     * @ORM\Column(name="email", type="text", nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="text", nullable=true)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", nullable=true)
     */
    private $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="birth_town", type="text", nullable=true)
     */
    private $birthTown;

    /**
     * @var string
     *
     * @ORM\Column(name="birth_province", type="text", nullable=true)
     */
    private $birthProvince;

    /**
     * @var string
     *
     * @ORM\Column(name="birth_country", type="string", length=2, nullable=true)
     */
    private $birthCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="vat", type="text", nullable=true)
     */
    private $vat;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_code", type="text", nullable=true)
     */
    private $taxCode;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=2, nullable=true)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="text", nullable=true)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="town", type="text", nullable=true)
     */
    private $town;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="address_info", type="text", nullable=true)
     */
    private $addressInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="text", nullable=true)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="text", nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="text", nullable=true)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="text", nullable=true)
     */
    private $fax;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted_ts", type="datetime", nullable=true)
     */
    private $insertedTs;

    /**
     * @var integer
     *
     * @ORM\Column(name="update_ts", type="bigint", nullable=true)
     */
    private $updateTs;

    /**
     * @var string
     *
     * @ORM\Column(name="pin", type="text", nullable=false)
     */
    private $pin;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="BusinessEmployee", mappedBy="employee")
     */
    private $businessEmployee;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
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
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @return string
     */
    public function getReadableBirthDate()
    {
        if ($this->birthDate instanceof \DateTime) {
            return $this->birthDate->format('d-m-Y');
        }
        return '-';
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function getBusinessPin()
    {
        $pins = json_decode($this->pin, true);
        return $pins['company'];
    }
}
