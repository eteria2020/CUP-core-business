<?php

namespace BusinessCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use BusinessCore\Entity\BusinessFleet;

/**
 * BusinessFleetRepository
 */
class BusinessFleetRepository extends EntityRepository {

    /**
     *
     * Return all BusinessFleet not dummy (id < 100)
     * @return BusinessFleet[]
     */
    public function getFeetNoDummy() {
        $em = $this->getEntityManager();

        $dql = "SELECT f
        FROM \BusinessCore\Entity\BusinessFleet f
        WHERE f.id < " . BusinessFleet::DUMMY_FLEET_LIMIT;

        $query = $em->createQuery($dql);

        return $query->getResult();
    }

}
