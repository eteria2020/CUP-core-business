<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\BusinessEmployee;
use BusinessCore\Entity\Group;
use BusinessCore\Entity\Repository\BusinessEmployeeRepository;
use BusinessCore\Entity\Repository\BusinessRepository;
use BusinessCore\Entity\Repository\GroupRepository;

use BusinessCore\Form\InputData\GroupDataFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Zend\Mvc\I18n\Translator;

class GroupService
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var BusinessEmployeeRepository
     */
    private $businessEmployeeRepository;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param GroupRepository $groupRepository
     * @param BusinessEmployeeRepository $businessEmployeeRepository
     * @param Translator $translator
     */
    public function __construct(
        EntityManager $entityManager,
        GroupRepository $groupRepository,
        BusinessEmployeeRepository $businessEmployeeRepository,
        Translator $translator
    ) {
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->groupRepository = $groupRepository;
        $this->businessEmployeeRepository = $businessEmployeeRepository;
    }

    public function getGroupById($groupId)
    {
        $group = $this->groupRepository->find($groupId);
        if ($group instanceof Group) {
            return $group;
        } else {
            throw new EntityNotFoundException($this->translator->translate("Nessun gruppo con questo id"));
        }
    }

    /**
     * @param array $userIdsToAdd
     * @param Group $group
     * @return int
     */
    public function addEmployeesToGroup(array $userIdsToAdd, Group $group)
    {
        $nInsert = 0;
        $businessEmployees = $this->businessEmployeeRepository->findBy([
            'employee' => $userIdsToAdd,
            'business' => $group->getBusiness()->getCode()
        ]);

        /** @var BusinessEmployee $businessEmployee */
        foreach ($businessEmployees as $businessEmployee) {
                $businessEmployee->assignToGroup($group);
                $this->entityManager->persist($businessEmployee);
                $this->entityManager->flush();
                $nInsert++;
        }
        return $nInsert;
    }

    public function removeEmployeeFromGroup(Group $group, $employeeId)
    {
        $businessEmployee = $this->businessEmployeeRepository->find([
            'employee' => $employeeId,
            'business' => $group->getBusiness()->getCode()
        ]);
        $businessEmployee->removeGroup();
        $this->entityManager->persist($businessEmployee);
        $this->entityManager->flush();
    }

    public function createNewGroup(Business $business, $data)
    {
        $group = GroupDataFactory::groupfromArray($business, $data);

        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }

    public function setGroupLimit(Group $group, $data)
    {
        $daily = $data['daily'];
        $weekly = $data['weekly'];
        $monthly = $data['monthly'];
        $dailyLimit = is_numeric($daily) && $daily >= 0 ? $daily : null;
        $weeklyLimit = is_numeric($weekly) && $weekly >= 0 ? $weekly : null;
        $monthlyLimit = is_numeric($monthly) && $monthly >= 0 ? $monthly : null;

        $group->setDailyMinutesLimit($dailyLimit);
        $group->setWeeklyMinutesLimit($weeklyLimit);
        $group->setMonthlyMinutesLimit($monthlyLimit);

        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }
}
