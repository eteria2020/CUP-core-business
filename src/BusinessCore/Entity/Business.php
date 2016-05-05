<?php

namespace BusinessCore\Entity;


use BusinessCore\Form\InputData\BusinessConfigParams;
use BusinessCore\Form\InputData\BusinessDetails;
use Doctrine\ORM\Mapping as ORM;
use Zend\Validator\Hostname;

/**
 * Business
 *
 * @ORM\Table(name="business", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessRepository")

 */
class Business
{
    const TYPE_WIRE_TRANSFER = 'wire_transfer';
    const TYPE_CREDIT_CARD = 'credit_card';

    const FREQUENCE_WEEKLY = 'weekly';
    const FREQUENCE_FORTNIGHTLY = 'fortnightly';
    const FREQUENCE_MONTHLY = 'monthly';

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=6, nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=false)
     */
    private $name;

    /**
     * @var array
     *
     * @ORM\Column(name="domains", type="json_array", nullable=true)
     */
    private $domains = [];

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=128, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=12, nullable=false)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=2, nullable=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=64, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="vat_number", type="string", length=64, nullable=false)
     */
    private $vatNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string",  length=64, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=64, nullable=false)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_type", type="string")
     */
    private $paymentType;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_frequence", type="string")
     */
    private $paymentFrequence;

    /**
     * @var bool
     *
     * @ORM\Column(name="business_mail_control", type="boolean")
     */
    private $businessMailControl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inserted_ts", type="datetime", nullable=false)
     */
    private $insertedTs;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_ts", type="datetime")
     */
    private $updatedTs;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="BusinessEmployee", mappedBy="business")
     */
    private $businessEmployee;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Group", mappedBy="business")
     */
    private $businessGroups;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="BusinessTimePackage", mappedBy="business")
     */
    private $businessTimePackages;

    /**
     * Bidirectional - One-To-One
     *
     * @ORM\OneToMany(targetEntity="BusinessFare", mappedBy="business")
     */
    private $businessFares;

    public function __construct($code)
    {
        $this->code = $code;
        $this->insertedTs = date_create();

    }

    public static function fromBusinessDetailsAndParams(
        $code,
        BusinessDetails $businessDetails,
        BusinessConfigParams $businessParams
    ) {
        $business = new Business($code);
        $business->updateDetails($businessDetails);
        $business->updateParams($businessParams);
        return $business;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return \DateTime
     */
    public function getInsertedTs()
    {
        return $this->insertedTs;
    }

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
    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    /**
     * @return string
     */
    public function getDomains()
    {
        return $this->domains;
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
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
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
    public function getProvince()
    {
        return $this->province;
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
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedTs()
    {
        return $this->updatedTs;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @return string
     */
    public function getPaymentFrequence()
    {
        return $this->paymentFrequence;
    }

    /**
     * @return boolean
     */
    public function isBusinessMailControlEnabled()
    {
        return $this->businessMailControl;
    }

    /**
     * @return BusinessEmployee[]
     */
    public function getBusinessEmployees()
    {
        return $this->businessEmployee;
    }

    public function updateDetails(BusinessDetails $data)
    {
        $this->name = $data->getName();
        $this->domains = $data->getDomains();
        $this->address = $data->getAddress();
        $this->zipCode = $data->getZipCode();
        $this->province = $data->getProvince();
        $this->city = $data->getCity();
        $this->vatNumber = $data->getVatNumber();
        $this->email = $data->getEmail();
        $this->phone = $data->getPhone();
        $this->fax = $data->getFax();
    }

    public function updateParams(BusinessConfigParams $data)
    {
        $this->paymentType = $data->getPaymentType();
        $this->paymentFrequence = $data->getPaymentFrequence();
        $this->businessMailControl = $data->getBusinessMailControl();
    }

    public function getPendingBusinessEmployees()
    {
        $result = [];
        /** @var BusinessEmployee $be */
        foreach ($this->businessEmployee as $be) {
            if ($be->isPending()) {
                $result[] = $be;
            }
        }
        return $result;
    }

    public function getApprovedBusinessEmployees()
    {
        $result = [];
        /** @var BusinessEmployee $be */
        foreach ($this->businessEmployee as $be) {
            if ($be->isApproved() || $be->isBlocked()) {
                $result[] = $be;
            }
        }
        return $result;
    }

    public function getEnabledBusinessEmployeesWithoutGroup()
    {
        $result = [];
        /** @var BusinessEmployee $be */
        foreach ($this->businessEmployee as $be) {
            if ($be->isApproved() && is_null($be->getGroup())) {
                $result[] = $be;
            }
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function getBusinessGroups()
    {
        return $this->businessGroups;
    }

    /**
     * @return BusinessTimePackage[]
     */
    public function getBusinessTimePackages()
    {
        return $this->businessTimePackages;
    }

    /**
     * @return BusinessFare
     */
    public function getActiveBusinessFare()
    {
        /** @var BusinessFare $latestFare */
        $latestFare = $this->businessFares[0];
        /** @var BusinessFare $businessFare */
        foreach ($this->businessFares as $businessFare) {
            if ($businessFare->getInsertedTs() > $latestFare->getInsertedTs()) {
                $latestFare = $businessFare;
            }
        }
        return $latestFare;
    }
}
