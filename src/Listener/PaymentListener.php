<?php

namespace BusinessCore\Listener;

use BusinessCore\Service\BusinessService;
use BusinessCore\Service\ContractService;
use BusinessCore\Service\TransactionService;
use MvlabsPayments\Transaction;
use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\SharedListenerAggregateInterface;

class PaymentListener implements SharedListenerAggregateInterface
{
    /**
     * @var array
     */
    private $listeners = [];
    /**
     * @var TransactionService
     */
    private $transactionService;
    /**
     * @var ContractService
     */
    private $contractService;
    /**
     * @var BusinessService
     */
    private $businessService;

    public function __construct(
        TransactionService $transactionService,
        ContractService $contractService,
        BusinessService $businessService
    ) {
        $this->transactionService = $transactionService;
        $this->contractService = $contractService;
        $this->businessService = $businessService;
    }

    public function attachShared(SharedEventManagerInterface $events)
    {

        $this->listeners[] = $events->attach(
            'PaymentService',
            'contractCreated',
            [$this, 'contractCreated']
        );

        $this->listeners[] = $events->attach(
            'PaymentService',
            'firstTransactionCompleted',
            [$this, 'firstTransactionCompleted']
        );

        $this->listeners[] = $events->attach(
            'PaymentService',
            'transactionCompleted',
            [$this, 'transactionCompleted']
        );

        $this->listeners[] = $events->attach(
            'PaymentService',
            'transactionFailed',
            [$this, 'transactionFailed']
        );

        $this->listeners[] = $events->attach(
            'PaymentService',
            'transactionCreated',
            [$this, 'transactionCreated']
        );
    }

    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach($index, $callback)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function transactionCreated(EventInterface $e)
    {
        $params = $e->getParams();
        $paymentRequest = $params['paymentRequest'];
        $transaction = $params['transaction'];
        $this->transactionService->assignTransactionToPayments($paymentRequest, $transaction);
    }

    public function contractCreated(EventInterface $e)
    {
        $params = $e->getParams();
        $customerContract = $params['customerContract'];
        $businessCode = $customerContract->customer()->id();
        $business = $this->businessService->getBusinessByCode($businessCode);
        $this->contractService->createContract($customerContract, $business);
    }

    public function firstTransactionCompleted(EventInterface $e)
    {
        $params = $e->getParams();
        $transactionId = $params['transactionId'];
        $contractId = $params['contractId'];
        $contract = $this->contractService->findById($contractId);
        $transaction = $this->transactionService->getTransactionFromId($transactionId);
        $this->transactionService->firstTransactionCompleted($transaction, $contract, $params);
    }

    public function transactionCompleted(EventInterface $e)
    {
        $params = $e->getParams();
        /** @var Transaction $transaction */
        $transaction = $params['transaction'];
        $businessTransaction = $this->transactionService->getTransactionFromId($transaction->id());
        $this->transactionService->transactionCompleted($businessTransaction);
    }

    public function transactionFailed(EventInterface $e)
    {
        $params = $e->getParams();
        $transaction = $params['transaction'];
        $businessTransaction = $this->transactionService->getTransactionFromId($transaction->id());
        $this->transactionService->transactionFailed($businessTransaction);
    }
}
