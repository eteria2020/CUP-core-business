<?php

namespace BusinessCore\Payment\Factory;

use MvlabsPayments\Parameters;
use MvlabsPayments\Payments\FirstPayment;
use Omnipay\Cartasi\Gateway;
use Zend\EventManager\EventManagerInterface;

class FirstPaymentFactory
{
    public static function firstPayment(EventManagerInterface $eventManager, Parameters $parameters)
    {
        $firstPaymentGateway = new Gateway();
        //TODO TEST
        $firstPaymentGateway->setTestMode(true);

        return new FirstPayment($eventManager, $firstPaymentGateway, $parameters);
    }
}
