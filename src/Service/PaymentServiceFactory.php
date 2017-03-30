<?php

namespace BusinessCore\Service;

use BusinessCore\Payment\Factory\FirstPaymentFactory;
use BusinessCore\Payment\Factory\PaymentFactory;
use BusinessCore\Payment\Factory\TokenPaymentFactory;
use MvlabsPayments\Parameters;
use MvlabsPayments\Payments\FirstPayment;
use MvlabsPayments\Payments\Payment;
use MvlabsPayments\Payments\TokenPayment;
use Zend\EventManager\EventManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PaymentServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $eventManager = new EventManager('PaymentService');

        $parameters = $serviceLocator->get('Config')['omnipay'];
        $firstPaymentParameters = new Parameters($parameters['first-payment']);
        $tokenPaymentParameters = new Parameters($parameters['token-payment']);

        $testMode = $parameters['environment'] != 'production';

        $firstPayment = FirstPaymentFactory::firstPayment($eventManager, $firstPaymentParameters, $testMode);
        $tokenPayment = TokenPaymentFactory::tokenPayment($eventManager, $tokenPaymentParameters, $testMode);
        $payment = PaymentFactory::payment($firstPayment, $tokenPayment);

        return new PaymentService($payment);
    }
}
