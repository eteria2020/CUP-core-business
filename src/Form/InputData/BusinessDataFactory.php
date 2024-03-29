<?php

namespace BusinessCore\Form\InputData;


use BusinessCore\Entity\BusinessFleet;
use BusinessCore\Exception\InvalidBusinessFormException;
use BusinessCore\Form\Validator\VatNumber;
use BusinessCore\Form\Validator\ZipCode;
use Zend\Mvc\I18n\Translator;
use Zend\Validator\EmailAddress;
use Zend\Validator\Hostname;

class BusinessDataFactory
{
    /**
     * @param array $data
     * @return BusinessDetails
     * @throws InvalidBusinessFormException
     */
    public static function businessDetailsfromArray(array $data)
    {
        $name = $data['name'];
        $domains = $data['domains'];
        $address = $data['address'];
        $zipCode = $data['zipCode'];
        $province = $data['province'];
        $city = $data['city'];
        $vatNumber = $data['vatNumber'];
        $email = empty($data['email']) ? null : $data['email'];
        $phone = empty($data['phone']) ? null : $data['phone'];
        $fax = empty($data['fax']) ? null : $data['fax'];

        if (empty($name)) {
            throw new InvalidBusinessFormException("L'azienda deve avere un nome");
        }

        if (empty($address)) {
            throw new InvalidBusinessFormException("L'azienda deve avere un indirizzo");
        }

        if (empty($zipCode)) {
            throw new InvalidBusinessFormException("L'azienda deve avere un CAP");
        }

        if (empty($province)) {
            throw new InvalidBusinessFormException("L'azienda deve avere una provincia");
        }

        if (empty($province)) {
            throw new InvalidBusinessFormException("L'azienda deve avere una provincia");
        }

        $validator = new EmailAddress();
        if (!is_null($email) && !$validator->isValid($email)) {
            throw new InvalidBusinessFormException("L'email inserita non è valida");
        }

        $validator = new VatNumber();
        if (!$validator->isValid($vatNumber)) {
            throw new InvalidBusinessFormException(implode(" ", $validator->getMessages()));
        }

        $validator = new ZipCode();
        if (!$validator->isValid($zipCode)) {
            throw new InvalidBusinessFormException(implode(" ", $validator->getMessages()));
        }

        $validator = new Hostname();
        $domains = explode(" ", $domains);
        $domains = array_filter($domains);
        foreach ($domains as $domain) {
            if (!$validator->isValid($domain)) {
                throw new InvalidBusinessFormException("I domini inseriti non sono validi, devono essere nel formato 'example.com'");
            }
        }
        return new BusinessDetails(
            $name,
            $domains,
            $address,
            $zipCode,
            $province,
            $city,
            $vatNumber,
            $email,
            $phone,
            $fax
        );
    }

    public static function businessConfigParamsfromArray(array $data)
    {
        $translator = new \Zend\I18n\Translator\Translator;
        if (array_key_exists('businessEnabled', $data)) {
            $businessEnabled = $data['businessEnabled'];
        } else {
            $businessEnabled = true;
        }

        $paymentType = $data['paymentType'];
        $paymentFrequence = $data['paymentFrequence'];
        $invoiceFrequence = $data['invoiceFrequence'];
        $businessMailControl = $data['businessMailControl'];
        $subscriptionFeeCents = $data['subscriptionFeeCents'];
        $fleet = $data['fleet'];


        if (empty($paymentType)) {
            $paymentType = null;
        }
        if (empty($paymentFrequence)) {
            $paymentFrequence = null;
        }
        if (empty($invoiceFrequence)) {
            $invoiceFrequence = null;
        }
        if (is_nan($businessMailControl)) {
            throw new InvalidBusinessFormException($translator->translate("Si è verificato un errore"));
        }

        if (is_nan($subscriptionFeeCents) || $subscriptionFeeCents < 1) {
            throw new InvalidBusinessFormException($translator->translate("La quota di iscrizione deve essere di almeno 1€"));
        }

        if (!$fleet instanceof BusinessFleet) {
            throw new InvalidBusinessFormException($translator->translate("Flotta non trovata"));
        }

        $subscriptionFeeCents = $subscriptionFeeCents * 100;

        return new BusinessConfigParams($businessEnabled, $paymentType, $paymentFrequence, $invoiceFrequence, $businessMailControl, $subscriptionFeeCents, $fleet);
    }
}
