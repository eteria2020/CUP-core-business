<?php

namespace BusinessCore\Form\InputData;

class BusinessDetails
{
    private $name;
    private $domains;
    private $address;
    private $zipCode;
    private $province;
    private $city;
    private $vatNumber;
    private $email;
    private $phone;
    private $fax;
    private $recipientCode;
    private $cem;

    /**
     * BusinessDetails constructor.
     * @param $name
     * @param $domains
     * @param $address
     * @param $zipCode
     * @param $province
     * @param $city
     * @param $vatNumber
     * @param $email
     * @param $phone
     * @param $fax
     * @param null $recipientCode
     * @param null $cem
     */
    public function __construct(
        $name,
        $domains,
        $address,
        $zipCode,
        $province,
        $city,
        $vatNumber,
        $email,
        $phone,
        $fax,
        $recipientCode = null,
        $cem = null
    ) {
        $this->name = $name;
        $this->domains = $domains;
        $this->address = $address;
        $this->zipCode = $zipCode;
        $this->province = $province;
        $this->city = $city;
        $this->vatNumber = $vatNumber;
        $this->email = $email;
        $this->phone = $phone;
        $this->fax = $fax;
        $this->recipientCode = $recipientCode;
        $this->cem = $cem;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDomains()
    {
        return $this->domains;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @return mixed
     */
    public function getRecipientCode()
    {
        return $this->recipientCode;
    }

    /**
     * @return mixed
     */
    public function getCem()
    {
        return $this->cem;
    }
}

