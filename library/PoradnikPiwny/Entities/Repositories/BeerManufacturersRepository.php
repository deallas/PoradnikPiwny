<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\NoResultException,
    PoradnikPiwny\Entities\BeerManufacturer,
    PoradnikPiwny\Entities\BeerManufacturerTranslation;

class BeerManufacturersRepository extends AbstractPaginatorRepository
{   
    private static $_db_orders = array(
        'manufacturer_name' => 'bm.name',
        'manufacturer_website' => 'bm.website',
        'manufacturer_email' => 'bm.email',
        'manufacturer_address' => 'bm.address',
        'manufacturer_latitude' => 'bm.latitude',
        'manufacturer_longitude' => 'bm.longitude',
        'country_name' => 'c.name',
        'region_name' => 'r.name',
        'city_name' => 'cit.name'
    ); 
    
    /* ---------------------------------------------------------------------- */
    
    public function getAvailableItems() {
        return array(10, 20, 30, 50);
    }

    public function getAvailableOrders() {
        return array(
            'manufacturer_name',
            'manufacturer_website',
            'manufacturer_email',
            'manufacturer_address',
            'manufacturer_latitude',
            'manufacturer_longitude',
            'country_name',
            'region_name',
            'city_name'
        );
    }

    public function getDefaultOrders() {
        return array(
            'manufacturer_name'
        );
    }
     
    public function getOptionsPaginatorName()
    {
        return 'beer_manufacturer_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'manufacturer_name';
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param array $options
     * @param PoradnikPiwny\Entities\BeerDistributor $distributor
     * @return \Zend_Paginator
     */
    public function getPaginator($options, $distributor) 
    {
        if(!isset(self::$_db_orders[$options['order']])) {
            $dbOrder = self::$_db_orders[$this->getDefaultOrder()];
        } else {
            $dbOrder = self::$_db_orders[$options['order']];
        }
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bm', 'c', 'r', 'cit', 'bmi'))
            ->from('\PoradnikPiwny\Entities\BeerManufacturer', 'bm')
            ->leftJoin('bm.country', 'c')
            ->leftJoin('bm.region', 'r')
            ->leftJoin('bm.city', 'cit')
            ->leftJoin('bm.beerManufacturerImage', 'bmi')
            ->where('bm.distributor=:distributor')
            ->setParameter('distributor', $distributor)
            ->orderBy($dbOrder, ($options['desc'] == null) ? 'ASC' : 'DESC');
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($options['page']);
        $paginator->setItemCountPerPage($options['items']);
        
        return $paginator;
    }

    /**
     * @param PoradnikPiwny\Entities\BeerDistributor $distributor
     * @return array
     */
    public function getSortedManufacturers($distributor)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('bp')
            ->from('\PoradnikPiwny\Entities\BeerManufacturer', 'bp')
            ->where('bp.distributor = :distributor')
            ->setParameter('distributor', $distributor)
            ->orderBy('bp.name');   
          
        $rows = array();       
        foreach($qb->getQuery()->getResult() as $row)
        {
            $rows[$row->getId()] = $row;
        }
        
        return $rows;
    }
    
    /**
     * @param PoradnikPiwny\Entities\BeerDistributor $distributor
     * @return array
     */
    public function getAssocSortedManufacturers($distributor)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('bp')
            ->from('\PoradnikPiwny\Entities\BeerManufacturer', 'bp')
            ->where('bp.distributor = :distributor')
            ->setParameter('distributor', $distributor)
            ->orderBy('bp.name');   
        
        $rows = array();       
        foreach($qb->getQuery()->getArrayResult() as $manufacturer)
        {
            $rows[$manufacturer['id']] = $manufacturer['name'];
        }
        
        return $rows;
    }
    
    /**
     * Dodaje wytwórcę
     * 
     * @param string $name
     * @param string $website
     * @param string $email
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @param array $descriptions
     * @param \PoradnikPiwny\Entities\Country $country
     * @param \PoradnikPiwny\Entities\Region $region
     * @param \PoradnikPiwny\Entities\City $city
     * @param string $address
     * @param float $longitude
     * @param float $latitude
     */
    public function addManufacturer($name, $website, $email,
                                    $distributor, $descriptions,
                                    $country, $region, $city, $address,
                                    $longitude, $latitude)
    {
        $manufacturer = new BeerManufacturer($name, $website, $email, $distributor, 
                                             $country, $region, $city, $address,
                                             $longitude, $latitude);
        
        foreach($descriptions as $lang => $description)
        {
            $this->addManufacturerTranslation($manufacturer, $lang, $description);
        }
        
        $this->_em->persist($manufacturer);
        $this->_em->flush();
    }
    
    /**
     * Edytuje wytwórcę
     * 
     * @param \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     * @param string $name
     * @param string $website
     * @param string $email
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @param array $descriptions
     * @param \PoradnikPiwny\Entities\Country $country
     * @param \PoradnikPiwny\Entities\Region $region
     * @param \PoradnikPiwny\Entities\City $city
     * @param string $address
     * @param float $longitude
     * @param float $latitude
     */
    public function editManufacturer($manufacturer, $name, $website, $email,
                                    $distributor,$descriptions, $country, 
                                    $region, $city, $address, $longitude, $latitude)
    {
        $manufacturer->setName($name)
                     ->setWebsite($website)
                     ->setEmail($email)
                     ->setDistributor($distributor)
                     ->setCountry($country)
                     ->setRegion($region)
                     ->setCity($city)
                     ->setAddress($address)
                     ->setLatitude($latitude)
                     ->setLongitude($longitude);
        
        foreach($manufacturer->getManufacturerTranslations() as $trans)
        {
            $trans->setDescription($descriptions[$trans->getLang()]);
            $this->_em->persist($trans);
        }
        
        $this->_em->persist($manufacturer);
        $this->_em->flush();
    }
    
    /**
     * Usuwa wytwórcę
     * 
     * @param \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     */
    public function removeManufacturer($manufacturer)
    {
        $this->_em->remove($manufacturer);
        $this->_em->flush();
    }
    
    /**
     * Zwraca wytwórcę na podstawie id
     * 
     * @param int $id
     * @param boolean $forceNull
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerImageNotFoundException
     */
    public function getBeerManufacturerById($id, $forceNull = false)
    {
        if(!\Zend_Validate::is($id, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        $manufacturer = $this->_em->find('\PoradnikPiwny\Entities\BeerManufacturer', intval($id));
        
        if(!$manufacturer)
        {
            throw new \PoradnikPiwny\Exception\BeerManufacturerNotFoundException();
        }

        return $manufacturer;
    }
    
    /**
     * Dodaje tłumaczenie do określonego wytwórcy
     * 
     * @param \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     * @param string $lang
     * @param string $description
     */
    public function addManufacturerTranslation(BeerManufacturer $manufacturer, 
                                                  $lang, $description)
    {
        $mTrans = new BeerManufacturerTranslation($manufacturer, $lang, $description);
        
        $this->_em->persist($mTrans);
    }
    
    /**
     * Sprawdza unikalność wytwórcy u danego dystrybutora
     * 
     * @param string $manufacturerName
     * @param int $distributorId
     * @param int $excludeId
     * @return boolean
     */
    public function isManufacturerUnique($manufacturerName, 
                                           $distributorId, $excludeId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('bm')
           ->from('\PoradnikPiwny\Entities\BeerManufacturer', 'bm')
           ->leftJoin('bm.distributor', 'bd')
           ->where('bm.name = :manufacturerName')
           ->setParameter('manufacturerName', $manufacturerName)
           ->andWhere('bd.id = :distributorId')
           ->setParameter('distributorId', $distributorId);
        
        try {
            $result = $qb->getQuery()->getSingleResult();
            if($result->getId() == $excludeId) {
                return false;
            }
            return true;
        } catch(NoResultException $ex) {
            return false;
        }  
    }
}
