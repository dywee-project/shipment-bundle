<?php

namespace Dywee\ShipmentBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ShipmentRuleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShipmentRuleRepository extends EntityRepository
{
    public function findForQuantityMax()
    {
        $qb = $this->createQueryBuilder('sr')
            ->select('sr')
            ->where('sr.operator like :operator and sr.mappedKey = :mappedKey')
            ->setParameter('operator', '<%')
            ->setParameter('mappedKey', 'quantity')
            ;

        return $qb->getQuery()->getResult();
    }
}
