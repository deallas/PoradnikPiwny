<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\Query\ResultSetMapping,
    Doctrine\ORM\NoResultException;

class CitiesRepository extends EntityRepository
{   
    /**
     * @param \PoradnikPiwny\Entities\Region $region
     * @param string $reg
     * @param integer $limit
     * @return array
     */
    public function getAssocSortedCities($region, $reg, $limit)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('\PoradnikPiwny\Entities\City', 'r');
        $rsm->addFieldResult('r', 'citk_1_id', 'id');
        $rsm->addFieldResult('r', 'cit_name', 'name');
        $rsm->addFieldResult('r', 'cit_latitude', 'latitude');
        $rsm->addFieldResult('r', 'cit_longitude', 'longitude');

        $sql = 'SELECT citk_1_id, cit_name, cit_latitude, cit_longitude 
                FROM cities 
                WHERE reg_1_id=:id AND cit_name LIKE :reg 
                LIMIT :limit';
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter('id', $region->getId());
        $reg = $reg . '%';
        $query->setParameter('reg', $reg);
        $query->setParameter('limit', $limit);
        
        return $query->getArrayResult();
    }
    
    /**
     * @param int $cityId
     * @param int $regionId
     * @return \PoradnikPiwny\Entities\City
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\CityNotFoundException
     */
    public function getCityByIdAndRegionId($cityId, $regionId, $forceNull = false)
    {
        if(!\Zend_Validate::is($cityId, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
            ->from('\PoradnikPiwny\Entities\City', 'c')
            ->where('c.id=:cityId')
            ->setParameter('cityId', intval($cityId))
            ->leftJoin('c.region', 'r')
            ->andWhere('r.id=:regionId')
            ->setParameter('regionId', intval($regionId));
        
        try {
            return $qb->getQuery()->getSingleResult();
        } catch(NoResultException $ex) {
            throw new \PoradnikPiwny\Exception\CityNotFoundException();
        } 
    }
}
