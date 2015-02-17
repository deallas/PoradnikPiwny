<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\Query\ResultSetMapping,
    Doctrine\ORM\NoResultException;

class RegionsRepository extends EntityRepository
{   
    /**
     * @param \PoradnikPiwny\Entities\Country $country
     * @param string $reg
     * @return array
     */
    public function getAssocSortedRegions($country, $reg)
    {      
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult('\PoradnikPiwny\Entities\Region', 'r');
        $rsm->addFieldResult('r', 'regk_1_id', 'id');
        $rsm->addFieldResult('r', 'reg_name', 'name');
        $sql = 'SELECT regk_1_id, reg_name 
                    FROM regions 
                    WHERE cou_1_id=:id 
                        AND reg_name LIKE :reg';
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter('id', $country->getId());
        $reg = $reg . '%';
        $query->setParameter('reg', $reg);
        
        return $query->getArrayResult();
    }
    
    /**
     * Pobranie regionu na podstawie id
     * 
     * @param int $countryId
     * @param boolean $forceNull
     * @return \PoradnikPiwny\Entities\Country
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\RegionNotFoundException
     */
    public function getRegionById($regionId, $forceNull = false)
    {
        if(!\Zend_Validate::is($regionId, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
            
        $region = $this->_em->find('\PoradnikPiwny\Entities\Region', intval($regionId));
        
        if($region == null)
        {
            throw new \PoradnikPiwny\Exception\RegionNotFoundException;
        }
        
        return $region;
    }
    
    /**
     * Pobranie regionu na podstawie id oraz id kraju
     * 
     * @param int $regionId
     * @param int $countryId
     * @return \PoradnikPiwny\Entities\Region
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\RegionNotFoundException
     */
    public function getRegionByIdAndCountryId($regionId, $countryId, $forceNull = false)
    {
        if(!\Zend_Validate::is($regionId, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('r', 'c'))
            ->from('\PoradnikPiwny\Entities\Region', 'r')
            ->where('r.id=:regionId')
            ->setParameter('regionId', intval($regionId))
            ->leftJoin('r.country', 'c')
            ->andWhere('c.id=:countryId')
            ->setParameter('countryId', intval($countryId));
        
        try {
            return $qb->getQuery()->getSingleResult();
        } catch(NoResultException $ex) {
            throw new \PoradnikPiwny\Exception\RegionNotFoundException;
        }  
    }
}
