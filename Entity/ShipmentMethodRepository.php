<?php

namespace Dywee\ShipmentBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ShipmentMethodRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShipmentMethodRepository extends EntityRepository
{
    public function myFindBy($country, $weight, $type = null)
    {
        $search = 'sm.country = :country and sm.active = 1 and sm.minWeight <= :weight and sm.maxWeight > :weight';
        $parameters = array('country' => is_numeric($country)?$country:$country->getId(), 'weight' => $weight);
        if($type)
        {
            $search .= ' and sm.type = :type';
            $parameters['type'] = substr($type, 0, 3);
        }

        $queryBuilder = $this->createQueryBuilder('sm')
            ->select('sm')
            ->where($search)
            ->setParameters($parameters);

        return $queryBuilder->getQuery()->getResult();
    }
}
