<?php

namespace BusinessCore\Listener;

use BusinessCore\Service\BusinessEmailService;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\Mvc\I18n\Translator;

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
    /**
     * @var Translator
     */
    private $translator;


    public function __construct(BusinessEmailService $emailService, Translator $translator)
    {
        $this->emailService = $emailService;
        $this->translator = $translator;
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
            $this->translator->translate("SHARENGO - ecco il tuo PIN aziendale"),
            $this->translator->translate("Sei stato approvato dall'azienda, questo è il tuo PIN aziendale: ") . $employee->getBusinessPin()
        );
    }
}
