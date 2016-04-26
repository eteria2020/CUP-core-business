<?php

namespace BusinessCore\Service;

use BusinessCore\Entity\Business;
use BusinessCore\Entity\Group;
use BusinessCore\Entity\Repository\BusinessEmployeeRepository;
use BusinessCore\Entity\Repository\BusinessRepository;
use BusinessCore\Entity\Repository\GroupRepository;

use BusinessCore\Exception\InvalidGroupFormException;
use Doctrine\ORM\EntityManager;
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
     * @var BusinessRepository
     */
    private $businessRepository;

    /**
     * @var BusinessEmployeeRepository
     */
    private $businessEmployeeRepository;

    /**
     * BusinessService constructor.
     * @param EntityManager $entityManager
     * @param GroupRepository $groupRepository
     * @param BusinessRepository $businessRepository
     * @param BusinessEmployeeRepository $businessEmployeeRepository
     * @param $translator
     */
    public function __construct(
        EntityManager $entityManager,
        GroupRepository $groupRepository,
        BusinessRepository $businessRepository,
        BusinessEmployeeRepository $businessEmployeeRepository,
        $translator
    ) {
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->groupRepository = $groupRepository;
        $this->businessRepository = $businessRepository;
        $this->businessEmployeeRepository = $businessEmployeeRepository;
    }

    public function getGroupById($groupId)
    {
        return $this->groupRepository->find($groupId);
    }

    /**
     * This function receive raw $data from the form post
     * selected users come in $data as 'add-1234' => 'on' where 1234 is the id of the user
     *
     * @param array $data
     * @param Group $group
     * @return int
     */
    public function addEmployeesToGroup(array $data, Group $group)
    {
        $nInsert = 0;
        foreach ($data as $key => $value) {
            if (substr($key, 0, 3) === 'add' && $value === 'on') {
                $employeeId = substr($key, 4);

                $businessEmployee = $this->businessEmployeeRepository->find([
                    'employee' => $employeeId,
                    'business' => $group->getBusiness()->getCode()
                    ]);
                $businessEmployee->setGroup($group);
                $this->entityManager->persist($businessEmployee);
                $this->entityManager->flush();
                $nInsert++;
            }
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
        $name = $data['name'];
        $description = $data['description'];

        if (empty($name)) {
            throw new InvalidGroupFormException($this->translator->translate("Il gruppo deve avere un nome"));
        }
        $group = new Group($business, $name, $description);

        $this->entityManager->persist($group);
        $this->entityManager->flush();
    }
}
