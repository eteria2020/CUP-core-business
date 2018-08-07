<?php

namespace BusinessCore\Form\InputData;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\Webuser;
use BusinessCore\Exception\InvalidBusinessUserFormException;
use Zend\Crypt\Password\Bcrypt;
use ZfcUser\Options\UserServiceOptionsInterface;

class BusinessUserFactory {

    public static function businessUserfromArrayAndOptions(Business $business, $data, UserServiceOptionsInterface $options) {
        $translator = new \Zend\I18n\Translator\Translator;
        $name = trim($data['name']);
        $email = strtolower(trim($data['email']));
        $password1 = $data['password1'];
        $password2 = $data['password2'];

        if (empty($name)) {
            throw new InvalidBusinessUserFormException($translator->translate("Il nome utente non può essere vuoto"));
        }

        if (strlen($password1) < 8 || strlen($password2) < 8) {
            throw new InvalidBusinessUserFormException($translator->translate("Password deve essere lunga almeno 8 caratteri"));
        }

        if ($password1 !== $password2) {
            throw new InvalidBusinessUserFormException($translator->translate("Le password inserite non coincidono"));
        }

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($options->getPasswordCost());
        $hashedPassword = $bcrypt->create($password1);

        return new Webuser($email, $name, $hashedPassword, $business, 'business');
    }

    public static function businessUserPasswordChange(Webuser $webuser, $data, UserServiceOptionsInterface $options) {
        $password1 = $data['password1'];
        $password2 = $data['password2'];

        $translator = new \Zend\I18n\Translator\Translator;
        if (strlen($password1) < 8 || strlen($password2) < 8) {
            throw new InvalidBusinessUserFormException($translator->translate("Password deve essere lunga almeno 8 caratteri"));
        }

        if ($password1 !== $password2) {
            throw new InvalidBusinessUserFormException($translator->translate("Le password inserite non coincidono"));
        }

        $bcrypt = new Bcrypt();
        $bcrypt->setCost($options->getPasswordCost());
        $hashedPassword = $bcrypt->create($password1);

        $webuser->setPassword($hashedPassword);

        return $webuser;
    }

}
