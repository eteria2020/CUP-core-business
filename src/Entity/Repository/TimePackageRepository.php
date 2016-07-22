<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\Business;
use Doctrine\ORM\EntityRepository;

/**
 * TimePackageRepository
 */
class TimePackageRepository extends EntityRepository
{
    public function findBuyableByBusiness(Business $business)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT tp FROM \BusinessCore\Entity\TimePackage tp, BusinessCore\Entity\BusinessBuyableTimePackage bbtp ' .
            'WHERE tp = bbtp.timePackage ' .
            'AND bbtp.business = :business'
        );

        $query->setParameter('business', $business);

        return $query->getResult();
    }

}
