<?php

namespace BusinessCore\Entity\Repository;

use BusinessCore\Entity\BusinessTrip;
use Doctrine\ORM\EntityRepository;

/**
 * BusinessTimePackageRepository
 */
class BusinessTimePackageRepository extends EntityRepository
{
    public function getTimePackagesForBusinessTrip(BusinessTrip $businessTrip)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT btp
            FROM \BusinessCore\Entity\BusinessTimePackage btp
            WHERE btp.residualMinutes > 0
            AND btp.business = :business '
        );

        $query->setParameter('business', $businessTrip->getBusiness());

        return $query->getResult();
    }
}
