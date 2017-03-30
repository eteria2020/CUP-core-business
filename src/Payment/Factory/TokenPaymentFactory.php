<?php

namespace BusinessCore\Payment\Factory;

use MvlabsPayments\Parameters;
use MvlabsPayments\Payments\TokenPayment;
use Omnipay\Cartasi\TokenGateway;
use Zend\EventManager\EventManagerInterface;

class TokenPaymentFactory
{
    public static function tokenPayment(EventManagerInterface $eventManager, Parameters $parameters, $testMode = true)
    {
        $tokenPaymentGateway = new TokenGateway();
        $tokenPaymentGateway->setTestMode($testMode);

        return new TokenPayment($eventManager, $tokenPaymentGateway, $parameters);
    }
}
