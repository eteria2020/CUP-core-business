<?php

namespace BusinessCore\Entity;

use BusinessCore\Form\InputData\BusinessConfigParams;
use BusinessCore\Form\InputData\BusinessDetails;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MvlabsPayments\Contract\Contract;
use MvlabsPayments\Contract\NoContract;
use MvlabsPayments\Customer;
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
     * @var string
     *
     * @ORM\Column(name="invoice_frequence", type="string")
     */
    private $invoiceFrequence;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_payment_execution", type="datetime", nullable=false)
     */
    private $lastPaymentExecution;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_invoice_execution", type="datetime", nullable=false)
     */
    private $lastInvoiceExecution;

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
     * @var integer
     *
     * @ORM\Column(name="subscription_fee_cents", type="integer", nullable=false)
     */
    private $subscriptionFeeCents;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_enabled", type="boolean", nullable=false)
     */
    private $isEnabled = false;

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
     * @var BusinessTimePackage[]
     *
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="BusinessTimePackage", mappedBy="business")
     */
    private $businessTimePackages;

    /**
     * @var BusinessBuyableTimePackage[]
     *
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="BusinessBuyableTimePackage", mappedBy="business")
     */
    private $businessBuyableTimePackages;

    /**
     * Bidirectional - One-To-One
     *
     * @ORM\OneToMany(targetEntity="BusinessFare", mappedBy="business")
     */
    private $businessFares;

    /**
     * @var BusinessFleet
     *
     * @ORM\ManyToOne(targetEntity="BusinessFleet")
     * @ORM\JoinColumn(name="fleet_id", referencedColumnName="id", nullable=false)
     */
    private $fleet;

    /**
     * @var BusinessContract[]
     *
     * @ORM\OneToMany(targetEntity="BusinessContract", mappedBy="business")
     */
    private $businessContracts;

    /**
     * @var string
     *
     * @ORM\Column(name="recipient_code", type="text", nullable=true)
     */
    private $recipientCode;

    /**
     * @var string
     *
     * @ORM\Column(name="cem", type="text", nullable=true)
     */
    private $cem;

    public function __construct($code)
    {
        $this->code = $code;
        $this->insertedTs = date_create();
        $this->businessContracts = new ArrayCollection();
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
     * @return array
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

    public function isEnabled()
    {
        return $this->isEnabled;
    }

    public function enable()
    {
        $this->isEnabled = true;
    }

    /**
     * 
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->isEnabled ;
    }

    /**
     * 
     * @param boolean $enabled
     */
        public function setEnabled($enabled)
    {
        $this->isEnabled =$enabled;
    }

    /**
     * @param BusinessDetails $data
     */
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
        $this->recipientCode = $data->getRecipientCode();
        $this->cem = $data->getCem();
    }

    /**
     * @param BusinessConfigParams $data
     */
    public function updateParams(BusinessConfigParams $data)
    {
        $this->isEnabled = $data->getBusinessEnabled();
        $this->paymentType = $data->getPaymentType();
        $this->paymentFrequence = $data->getPaymentFrequence();
        $this->invoiceFrequence = $data->getInvoiceFrequence();
        $this->businessMailControl = $data->getBusinessMailControl();
        $this->subscriptionFeeCents = $data->getSubscriptionFeeCents();
        $this->fleet = $data->getFleet();
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

    /**
     * blocked employees are still approved
     * @return BusinessEmployee[]
     */
    public function getApprovedBusinessEmployees()
    {
        $result = [];
        /** @var BusinessEmployee $be */
        foreach ($this->businessEmployee as $be) {
            if ($be->isApproved() || $be->isBlocked() || $be->isApprovedWaitingForBusinessApproval()) {
                $result[] = $be;
            }
        }
        return $result;
    }

    /**
     * Even though blocked employees are still approved I don't return them because it doesn't make
     * much sense to be able to change group of blocked employees
     * @return BusinessEmployee[]
     */
    public function getApprovedBusinessEmployeesWithoutGroup()
    {
        $result = [];
        /** @var BusinessEmployee $be */
        foreach ($this->businessEmployee as $be) {
            if (($be->isApproved() || $be->isApprovedWaitingForBusinessApproval()) && is_null($be->getGroup())) {
                $result[] = $be;
            }
        }
        return $result;
    }

    /**
     * @return BusinessEmployee[]
     */
    public function getApprovedBusinessEmployeeWaitingForBusinessEnabling()
    {
        $result = [];
        /** @var BusinessEmployee $be */
        foreach ($this->businessEmployee as $be) {
            if ($be->isApprovedWaitingForBusinessApproval()) {
                $result[] = $be;
            }
        }
        return $result;
    }

    /**
     * @return Group[]
     */
    public function getBusinessGroups()
    {
        return $this->businessGroups;
    }

    /**
     * @return BusinessFleet
     */
    public function getFleet()
    {
        return $this->fleet;
    }

    /**
     * @return BusinessFleet
     */
    public function getFleetId()
    {
        if ($this->fleet instanceof BusinessFleet) {
            return $this->fleet->getId();
        }
        return 0;
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
        if (count($this->businessFares) > 0) {
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
        return null;
    }

    /**
     * @return int
     */
    public function getSubscriptionFeeCents()
    {
        return $this->subscriptionFeeCents;
    }

    public function getReadableSubscriptionFee()
    {
        return number_format($this->subscriptionFeeCents / 100, 2, '.', '');
    }

    public function canApproveAutomatically(Employee $employee)
    {
        if ($this->businessMailControl) {
            $employeeEmailDomain = substr(strrchr($employee->getEmail(), "@"), 1);
            foreach ($this->domains as $domain) {
                if ($employeeEmailDomain == $domain) {
                    return true;
                }
            }
        }
        return false;
    }

    public function payWithCreditCard()
    {
        return $this->paymentType == Business::TYPE_CREDIT_CARD;
    }

    /**
     * @return Customer
     */
    public function getPaymentCustomer()
    {
        $contract = null;
        foreach ($this->businessContracts as $businessContract) {
            if ($businessContract->isActive()) {
                $contract = $businessContract->getPaymentContract();
            }
        }
        if (!$contract instanceof Contract) {
            $contract = new NoContract();
        }
        return new Customer($this->code, $contract);
    }

    public function canBuyTimePackage(TimePackage $timePackage)
    {
        foreach ($this->businessBuyableTimePackages as $businessBuyableTimePackage) {
            if ($businessBuyableTimePackage->getTimePackage() == $timePackage) {
                return true;
            }
        }
        return false;
    }

    public function hasActiveContract()
    {
        foreach ($this->businessContracts as $businessContract) {
            if ($businessContract->isActive()) {
                return true;
            }
        }
        return false;
    }

    public function getActiveContract()
    {
        foreach ($this->businessContracts as $businessContract) {
            if ($businessContract->isActive()) {
                return $businessContract;
            }
        }
        return false;
    }

    /**
     * @return BusinessBuyableTimePackage[]
     */
    public function getBusinessBuyableTimePackages()
    {
        return $this->businessBuyableTimePackages;
    }

    /**
     * @return string
     */
    public function getInvoiceFrequence()
    {
        return $this->invoiceFrequence;
    }

    /**
     * @return \DateTime
     */
    public function getLastPaymentExecution()
    {
        return $this->lastPaymentExecution;
    }

    /**
     * @return \DateTime
     */
    public function getLastInvoiceExecution()
    {
        return $this->lastInvoiceExecution;
    }

    public function invoiceExecuted()
    {
        $this->lastInvoiceExecution = date_create();
    }

    public function paymentExecuted()
    {
        $this->lastPaymentExecution = date_create();
    }

    /**
     * Electronic invoice, return recipeint code (codice destinatario)
     *
     * @return string
     */
    public function getRecipientCode() {
        return $this->recipientCode;
    }

    /**
     * Electronic invoice, return Certified EMail (Posta Elettronica Certificata PEC)
     *
     * @return string
     */
    public function getCem() {
        return $this->cem;
    }
    
}
