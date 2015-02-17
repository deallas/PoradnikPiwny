<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\NoResultException,
    PoradnikPiwny\Entities\Beer,
    PoradnikPiwny\Entities\BeerTranslation,
    PoradnikPiwny\Entities\User,
    PoradnikPiwny\Entities\Currency,
    PoradnikPiwny\Entities\BeerPrice,
    WS\Tool;

class BeersRepository extends AbstractPaginatorRepository
{   
    private static $_db_orders = array(
        'beer_name' => 'b.name',
        'beer_avg' => 'b.rankingAvg',
        'beer_weighted_avg' => 'b.rankingWeightedAvg',
        'distributor_name' => 'bd.name',
        'manufacturer_name' => 'bm.name',
        'country_name' => 'cou.name',
        'region_name' => 'reg.name',
        'city_name' => 'cit.name'
    );
    
    /* ---------------------------------------------------------------------- */
    
    public function getAvailableItems() {
        return array(10, 20, 30, 50);
    }

    public function getAvailableOrders() {
        return array(
            'beer_name',
            'beer_avg',
            'beer_weighted_avg',
            'distributor_name',
            'manufacturer_name',
            'country_name',
            'region_name',
            'city_name'
        );
    }

    public function getDefaultOrders() {
        return array(
            'beer_name',
            'manufacturer_name',
            'distributor_name',
            'beer_avg'  
        );
    }
    
    public function getOptionsPaginatorName()
    {
        return 'beer_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'beer_name';
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * Zwraca paginator z piwami
     * 
     * @param array $options
     * @param \PoradnikPiwny\Entities\User $user
     * @param boolean $forceActive
     * @return \Zend_Paginator
     */
    public function getBeersPaginator($options, User $user = null, 
                                        $forceActive = false) 
    {    
        if(!isset(self::$_db_orders[$options['order']])) {
            $dbOrder = self::$_db_orders[$this->getDefaultOrder()];
        } else {
            $dbOrder = self::$_db_orders[$options['order']];
        }
        
        $a_select = array('b', 'bm', 'bd', 'bi');
        
        $qb = $this->_em->createQueryBuilder();
        $qb->from('\PoradnikPiwny\Entities\Beer', 'b')
            ->leftJoin('b.manufacturer', 'bm')
            ->leftJoin('b.distributor', 'bd')
            ->leftJoin('b.country', 'cou')
            ->leftJoin('b.region', 'reg')
            ->leftJoin('b.city', 'cit')
            ->leftJoin('b.image', 'bi')
            ->orderBy($dbOrder, ($options['desc'] == null) ? 'ASC' : 'DESC');

        if($user != null)
        {
            $qb->leftJoin('b.beerRankings','br','WITH','br.user = :user')
               ->setParameter('user', $user);   
            $a_select[] = 'br';
        }
        
        if($forceActive)
        {
            $qb->where('b.status = :stat')
               ->setParameter('stat', Beer::STATUS_AKTYWNY);
        }
        
        $qb->select($a_select);
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($options['page']);
        $paginator->setItemCountPerPage($options['items']);
        
        return $paginator;
    }
    
    /**
     * Pobiera n-tą liczbę ostatnio dodanych piw
     * 
     * @param int $items
     * @param \PoradnikPiwny\Entities\User $user
     * @param boolean $forceActive
     * @return array
     */
    public function getLastAddedBeers($items = 10, $user = null, 
                                        $forceActive = false)
    {
        $a_select = array('b', 'bm', 'bd', 'bi');
        
        $qb = $this->_em->createQueryBuilder();
        $qb->from('\PoradnikPiwny\Entities\Beer', 'b')
            ->leftJoin('b.manufacturer', 'bm')
            ->leftJoin('b.distributor', 'bd')
            ->leftJoin('b.image', 'bi')
            ->orderBy('b.dateAdded', 'DESC')
            ->setMaxResults($items);
        
        if($user != null)
        {
            $qb->leftJoin('b.beerRankings','br','WITH','br.user = :user')
               ->setParameter('user', $user);   
            $a_select[] = 'br';
        }
                      
        if($forceActive)
        {
            $qb->where('b.status = :status')
               ->setParameter('status', Beer::STATUS_AKTYWNY);
        }
        
        $qb->select($a_select);
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Pobiera n-tą liczbę piw wg średniej ważonej
     * 
     * @param int $items
     * @param \PoradnikPiwny\Entities\User $user
     * @param boolean $forceActive
     * @return array
     */
    public function getTopBeers($items = 10, $user = null, 
                                 $forceActive = false)
    {
        $a_select = array('b', 'bm', 'bd', 'bi');
        
        $qb = $this->_em->createQueryBuilder();
        $qb->from('\PoradnikPiwny\Entities\Beer', 'b')
            ->leftJoin('b.manufacturer', 'bm')
            ->leftJoin('b.distributor', 'bd')
            ->leftJoin('b.image', 'bi')
            ->orderBy('b.rankingWeightedAvg', 'DESC')
            ->where('b.rankingWeightedAvg IS NOT NULL')
            ->setMaxResults($items);
        
        if($user != null)
        {
            $qb->leftJoin('b.beerRankings','br','WITH','br.user = :user')
               ->setParameter('user', $user);   
            $a_select[] = 'br';
        }
                      
        if($forceActive)
        {
            $qb->andWhere('b.status = :status')
               ->setParameter('status', Beer::STATUS_AKTYWNY);
        }
        
        $qb->select($a_select);

        return $qb->getQuery()->getResult();
    }
    
    /**
     * Dodaje piwo do bazy
     * 
     * @param string $name
     * @param array $descriptions
     * @param array $prices
     * @param float $alcohol
     * @param float $extract
     * @param string $malt
     * @param string $type
     * @param string $filtered
     * @param string $pasteurized
     * @param string $flavored
     * @param string $placeofbrew
     * @param string $status
     * @param \PoradnikPiwny\Entities\BeerFamily $family
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @param \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     * @param \PoradnikPiwny\Entities\Country $country
     * @param \PoradnikPiwny\Entities\Region $region
     * @param \PoradnikPiwny\Entities\City $city
     */
    public function addBeer($name, $descriptions, $prices, $alcohol, $extract, 
                         $malt, $type, $filtered, $pasteurized, $flavored,
                         $placeofbrew, $status, $family = null, $distributor = null,
                         $manufacturer = null, $country = null, 
                         $region = null, $city = null)
    {   
        $this->_em->getConnection()->beginTransaction(); // suspend auto-commit
        
        try {
            $beer = new Beer($name, $alcohol, $extract, 
                             $malt, $type, $filtered, $pasteurized, $flavored,
                             $placeofbrew, $status, $family, $distributor,
                             $manufacturer, $country, 
                             $region, $city);

            $this->_em->persist($beer);

            foreach($descriptions as $lang => $description)
            {
                $this->addBeerTranslation($beer, $lang, $description);
            }

            if(!empty($prices))
            {
                $hashes = array();
                foreach($prices as $p)
                {
                    $name = $p['sizeOfBottle'] . '_' . $p['currency']->getId();
                    if(isset($hashes[$name])) {
                        continue;
                    }

                    $hashes[$name] = true;
                    $this->addBeerPrice($beer, $p['sizeOfBottle'], $p['number'], $p['currency']);
                }
            }
            
            $this->_em->flush();
            $this->_em->getConnection()->commit();
        } catch (Exception $e) {
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            throw $e;
        }
    }
    
    /**
     * Edytuje piwo
     * 
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param string $name
     * @param array $descriptions
     * @param array $prices
     * @param float $alcohol
     * @param float $extract
     * @param string $malt
     * @param string $type
     * @param string $filtered
     * @param string $pasteurized
     * @param string $flavored
     * @param string $placeofbrew
     * @param string $status
     * @param \PoradnikPiwny\Entities\BeerFamily $family
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @param \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     * @param \PoradnikPiwny\Entities\Country $country
     * @param \PoradnikPiwny\Entities\Region $region
     * @param \PoradnikPiwny\Entities\City $city
     */
    public function editBeer($beer, $name, $descriptions, $prices, $alcohol, $extract, 
                         $malt, $type, $filtered, $pasteurized, $flavored,
                         $placeofbrew, $status, $family = null, $distributor = null,
                         $manufacturer = null, $country = null, 
                         $region = null, $city = null)
    {      
        $this->_em->getConnection()->beginTransaction(); // suspend auto-commit
        
        try {
            $beer->setName($name)
                 ->setAlcohol($alcohol)
                 ->setExtract($extract)
                 ->setMalt($malt)
                 ->setFamily($family)
                 ->setType($type)
                 ->setFiltered($filtered)
                 ->setPasteurized($pasteurized)
                 ->setFlavored($flavored)
                 ->setPlaceofbrew($placeofbrew)
                 ->setStatus($status)
                 ->setDistributor($distributor)
                 ->setManufacturer($manufacturer)
                 ->setCountry($country)
                 ->setRegion($region)
                 ->setCity($city);

            foreach($beer->getBeerTranslations() as $trans)
            {
                $trans->setDescription($descriptions[$trans->getLang()]);
                $this->_em->persist($trans);
            }

            // Stupid FIX
            $this->_em->getRepository('\PoradnikPiwny\Entities\BeerPrice')
                      ->removeAllPricesForBeer($beer);

            if(!empty($prices))
            {
                $hashes = array();
                foreach($prices as $p)
                {
                    $name = $p['sizeOfBottle'] . '_' . $p['currency']->getId();
                    if(isset($hashes[$name])) {
                        continue;
                    }

                    $hashes[$name] = true;
                    $this->addBeerPrice($beer, $p['sizeOfBottle'], $p['number'], $p['currency']);
                }
            }

            $this->_em->persist($beer);
            
            $this->_em->flush();
            $this->_em->getConnection()->commit();
        } catch (Exception $e) {
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            throw $e;
        }
    }
    
    /**
     * Usuwa piwo
     * 
     * @param \PoradnikPiwny\Entities\Beer $beer
     */
    public function removeBeer($beer)
    { 
        foreach($beer->getBeerImages() as $image)
        {
            unlink(UPLOAD_PATH . '/images/' . $image->getPath());
            Tool::rmdirRecursive(UPLOAD_CACHE_PATH . '/images/' . $image->getId());
        }
        
        $this->_em->remove($beer);
        $this->_em->flush();
    }
    
    /**
     * Zwraca piwo na podstawie id
     * 
     * @param int $id
     * @param boolean $forceNull
     * @param boolean $forceActive
     * @return \PoradnikPiwny\Entities\Beer
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerNotFoundException
     */
    public function getBeerById($id, $forceNull = false, $forceActive = false)
    {
        if(!\Zend_Validate::is($id, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        /* @var $beer \PoradnikPiwny\Entities\Beer */
        $beer = $this->_em->find('\PoradnikPiwny\Entities\Beer', intval($id));

        if(!$beer)
        {
            throw new \PoradnikPiwny\Exception\BeerNotFoundException();
        }
        
        if($forceActive && $beer->getStatus() != Beer::STATUS_AKTYWNY)
        {
            throw new \PoradnikPiwny\Exception\BeerNotFoundException();
        }
        
        return $beer;
    }
    
    /**
     * Dodaje tłumaczenie do określonego piwa
     * 
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param string $lang
     * @param string $description
     */
    public function addBeerTranslation(Beer $beer, $lang, $description)
    {
        $bTrans = new BeerTranslation($beer, $lang, $description);
        
        $this->_em->persist($bTrans);
    }
    
    /**
     * Pobiera opis piwa
     * 
     * @param int $beerId
     * @param string $lang
     * @throws \PoradnikPiwny\Exception\BeerTranslationNotFoundException
     * @return \PoradnikPiwny\Entites\BeerTranslation
     */
    public function getBeerTranslationByBeerIdAndLang($beerId, $lang)
    {
        $lang = strtoupper($lang);
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select('bt')
           ->from('\PoradnikPiwny\Entities\BeerTranslation', 'bt')
           ->leftJoin('bt.beer', 'b')
           ->where('b.id = :beerId')
           ->setParameter('beerId', intval($beerId))
           ->andWhere('bt.lang = :lang')
           ->setParameter('lang', $lang);
        
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch(NoResultException $exc) {
            throw new \PoradnikPiwny\Exception\BeerTranslationNotFoundException();
        }
        
        return $result;   
    }
    
    /**
     * Dodaje ceny za piwo w określonej walucie i rozmiarze butelki
     * 
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param int $sizeOfBottle
     * @param float $number,
     * @param \PoradnikPiwny\Entities\Currency $currency
     */
    public function addBeerPrice(Beer $beer, $sizeOfBottle, $number, Currency $currency)
    {
        $bPrice = new BeerPrice($sizeOfBottle, $number, $beer, $currency);
        
        $this->_em->persist($bPrice);
    }
}
