<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\EntityRepository;

class CountriesRepository extends EntityRepository
{   
    /**
     * Pobranie posortowanych krajów
     * 
     * @return array
     */
    public function getSortedCountriesName()
    {
        $dql = 'SELECT con
                FROM PoradnikPiwny\Entities\Country con INDEX BY con.id
                ORDER BY con.name';
        $qb = $this->_em->createQuery($dql);     
        
        return $qb->getResult();
    }
    
    /**
     * Pobranie kraju na podstawie id
     * 
     * @param int $countryId
     * @param boolean $forceNull
     * @return \PoradnikPiwny\Entities\Country
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\CountryNotFoundException
     */
    public function getCountryById($countryId, $forceNull = false)
    {
        if(!\Zend_Validate::is($countryId, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        $country = $this->_em->find('\PoradnikPiwny\Entities\Country', intval($countryId));
        
        if(!$country)
        {
            throw new \PoradnikPiwny\Exception\CountryNotFoundException();
        }
        
        return $country;
    }
}