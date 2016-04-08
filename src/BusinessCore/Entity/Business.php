<?php

namespace BusinessCore\Entity;

use BusinessCore\Form\InputData\BusinessData;
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
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="BusinessEmployee", mappedBy="business")
     */
    private $businessEmployee;


    public function __construct($code, BusinessData $data)
    {
        $this->code = $code;
        $this->insertedTs = date_create();
        $this->update($data);
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
     * @return mixed
     */
    public function getEmployees()
    {
        $associations = $this->businessEmployee;
        $result = [];

        foreach ($associations as $association) {
            $result[] = $association->getEmployee();
        }
        return $result;
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

    private function updateField($field, $value)
    {
        $this->{$field} = (empty($value) ? null : $value);
    }

    private function updateEmail($email)
    {
        $validator = new EmailAddress();
        if (!$validator->isValid($email)) {
            throw new InvalidElementException("L'email inserita non è valida");
        }
        $this->email = $email;
    }

    private function updateVatNumber($vat)
    {
        $validator = new VatNumber();
        if (!$validator->isValid($vat)) {
            throw new InvalidElementException("Partita IVA non valida");
        }
        $this->vatNumber = $vat;
    }

    private function updateDomains($domains)
    {
        $validator = new Hostname();
        $domains = explode(" ", $domains);
        $domains = array_filter($domains);
        foreach ($domains as $domain) {
            if (!$validator->isValid($domain)) {
                throw new InvalidElementException("I domini inseriti non sono validi, devono essere nel formato 'example.com'");
            }
        }
        $this->domains = $domains;
    }

    public function update($data)
    {
        foreach ($data as $key => $field) {

            $function = 'update' . ucfirst($key);
            if (method_exists($this, $function)) {
                $this->{$function}($field);
            } else if (property_exists($this, $key)) {
                $this->updateField($key, $field);
            }
        }
        $this->updatedTs = date_create();
    }
}
