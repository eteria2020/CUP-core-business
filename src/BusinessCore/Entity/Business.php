<?php

namespace BusinessCore\Entity;

use BusinessCore\Form\InputData\BusinessData;
use BusinessCore\Form\InputData\BusinessParams;
use BusinessCore\Form\InputData\BusinessPaymentParams;
use BusinessCore\Form\Validator\VatNumber;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Exception\InvalidElementException;
use Zend\Validator\EmailAddress;
use Zend\Validator\Hostname;

/**
 * Business
 *
 * @ORM\Table(name="business", schema="businesses")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessRepository")

 */
class Business
{
    const TYPE_WIRE_TRANSFER = 'Bonifico';
    const TYPE_CREDIT_CARD = 'Carta di credito';

    const FREQUENCE_WEEKLY = 'Settimanale';
    const FREQUENCE_FORTHNIGHTLY = 'Quindicinale';
    const FREQUENCE_MONTHLY = 'Mensile';

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
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
     * @ORM\Column(name="address", type="string", nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", nullable=false)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", nullable=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="vat_number", type="string", nullable=false)
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
     * @ORM\Column(name="phone", type="string", nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", nullable=false)
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
     * Business constructor.
     * @param $code
     */
    public function __construct($code)
    {
        $this->code = $code;
        $this->insertedTs = date_create();
    }

    public static function fromBusinessDataAndParams($code, BusinessData $businessData, BusinessPaymentParams $businessParams)
    {
        $business = new Business($code);
        $business->update($businessData);
        $business->update($businessParams);
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
    public function isBusinessMailControl()
    {
        return $this->businessMailControl;
    }

    /**
     * This dummy function has the sole purpose of allowing
     * the parser of POEdit to parse this translations
     */
    private function dummyFunctionForPoEdit()
    {
        $this->translate('Bonifico');
        $this->translate('Carta di credito');
        $this->translate('Settimanale');
        $this->translate('Quindicinale');
        $this->translate('Mensile');
    }

    public function update($data)
    {
        if ($data instanceof BusinessData) {
            $this->updateData($data);
        } else {
            $this->updateParams($data);
        }
        $this->updatedTs = date_create();
    }

    public function updateData(BusinessData $data)
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

    public function updateParams(BusinessPaymentParams $data)
    {
        $this->paymentType = $data->getPaymentType();
        $this->paymentFrequence = $data->getPaymentFrequence();
        $this->businessMailControl = $data->getBusinessMailControl();
    }
}
