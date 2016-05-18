<?php

namespace BusinessCore\Listener;

use BusinessCore\Service\BusinessEmailService;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\EventInterface;

class EmployeeApprovedListener implements SharedListenerAggregateInterface
{
    /**
     * @var array
     */
    private $listeners = [];

    /**
     * @var BusinessEmailService
     */
    private $emailService;


    public function __construct($emailService)
    {
        $this->emailService = $emailService;
    }

    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            'BusinessService',
            'employeeApproved',
            [$this, 'notifyApprovalToEmployee']
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

    public function notifyApprovalToEmployee(EventInterface $e)
    {
        $params = $e->getParams();
        $employee = $params['employee'];

        $this->emailService->sendEmail(
            $employee->getEmail(),
            "TEST",
            "TEST CONTENT"
        );
    }
}
