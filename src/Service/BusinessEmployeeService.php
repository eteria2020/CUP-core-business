<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Repository\BusinessEmployeeRepository;
use BusinessCore\Entity\Repository\BusinessRepository;
use BusinessCore\Entity\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManager;
use Zend\EventManager\EventManager;
use Zend\Mvc\I18n\Translator;
use BusinessCore\Service\BusinessEmailService;
use BusinessCore\Entity\Employee;
use BusinessCore\Entity\Mails;

class BusinessEmployeeService {

    /**
     *
     * @var Translator
     */
    private $translator;

    /**
     *
     * @var BusinessRepository
     */
    private $businessRepository;

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     *
     * @var BusinessEmployeeRepository
     */
    private $businessEmployeeRepository;

    /**
     *
     * @var EmployeeRepository
     */
    private $employeeRepository;

    /**
     *
     * @var EventManager
     */
    private $eventManager;

    /**
     *
     * @var BusinessEmailService
     */
    private $businessEmailService;

    public function __construct(
    EntityManager $entityManager, BusinessRepository $businessRepository, BusinessEmployeeRepository $businessEmployeeRepository, EmployeeRepository $employeeRepository, Translator $translator, EventManager $eventManager, BusinessEmailService $businessEmailService
    ) {
        $this->translator = $translator;
        $this->businessRepository = $businessRepository;
        $this->entityManager = $entityManager;
        $this->businessEmployeeRepository = $businessEmployeeRepository;
        $this->employeeRepository = $employeeRepository;
        $this->eventManager = $eventManager;
        $this->businessEmailService = $businessEmailService;
    }

    /**
     *
     * @param Employee $employee
     * @param int $category
     * @param string $language
     */
    public function sendEmailNotification($employee, $category, $language) {
        $mail = $this->businessEmailService->getMail($category, $language);
        $to = $employee->getEmail();
        $name = $employee->getName();
        $subject = $mail->getSubject();
        $template = $mail->getContent();
        $pin = $employee->getBusinessPin();

        switch ($category) {
            case 111 :  // employee PIN approved
                $content = sprintf($template, $name, $pin);
                break;
            default :
                $content = sprintf($template, $name, $pin);
        }

        $this->businessEmailService->sendEmail($to, $subject, $content);
    }

}
