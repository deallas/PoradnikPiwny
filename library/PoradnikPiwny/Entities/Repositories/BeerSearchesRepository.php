<?php

namespace PoradnikPiwny\Entities\Repositories;

use PoradnikPiwny\Entities\User,
    PoradnikPiwny\Entities\BeerSearch,
    PoradnikPiwny\Entities\Beer,
    Doctrine\ORM\NoResultException;

class BeerSearchesRepository extends AbstractPaginatorRepository
{          
    const FIELD_NAME_WEIGHT = 10;
    const FIELD_DESCRIPTION_WEIGHT = 5;
    const SEARCH_MAX_MATCHES = 50;  
    
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
        return 'beer_search_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'beer_name';
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * Zapisuje wartości pól z formularza wyszukującego
     * 
     * @param string $uid
     * @param string $query
     * @param \PoradnikPiwny\Entities\User $user
     * @param float $ranking_min
     * @param float $ranking_max
     * @param float $alcohol_min
     * @param float $alcohol_max
     * @param float $extract_min
     * @param float $extract_max
     * @param string $malt
     * @param string $type
     * @param string $filtered
     * @param string $pasteurized
     * @param string $flavored
     * @param string $placeOfBrew
     * @param \PoradnikPiwny\Entities\BeerFamily $family
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @param \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     * @param \PoradnikPiwny\Entities\Country $country
     * @param \PoradnikPiwny\Entities\Region $region
     * @param \PoradnikPiwny\Entities\City $city
     * 
     * @return \PoradnikPiwny\Entities\BeerSearch
     */
    public function addSearchValues($uid, $query, $user, $ranking_min, $ranking_max,
                                 $alcohol_min, $alcohol_max, $extract_min, $extract_max,
                                 $malt, $type, $filtered, $pasteurized,
                                 $flavored, $placeOfBrew, $family, $distributor,
                                 $manufacturer, $country, $region, $city)
    {
        $search = new BeerSearch($uid, $query, $user, $ranking_min, $ranking_max,
                                 $alcohol_min, $alcohol_max, $extract_min, $extract_max,
                                 $malt, $type, $filtered, $pasteurized,
                                 $flavored, $placeOfBrew, $family, $distributor,
                                 $manufacturer, $country, $region, $city);   
        
        $this->_em->persist($search);
        $this->_em->flush();
        
        return $search;
    }
    
    /**
     * Pobiera wartości pól formularza wyszukującego
     * 
     * @param string $uid
     * @throws \PoradnikPiwny\Exception\NullPointerException
     */
    public function getSearchValues($uid)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('bs')
           ->from('\PoradnikPiwny\Entities\BeerSearch', 'bs')
           ->where('bs.uid=:uid')
           ->setParameter('uid', $uid);

        try {
            $result = $qb->getQuery()->getSingleResult();  
        } catch(NoResultException $ex) {
            throw new \PoradnikPiwny\Exception\NullPointerException();
        }
        
        return $result;
    }
    
    /**
     * Wyszukuje piwa po wielu parametrach
     * 
     * @param string $uid
     * @param string $query
     * @param \PoradnikPiwny\Entities\User $user
     * @param float $ranking_min
     * @param float $ranking_max
     * @param float $alcohol_min
     * @param float $alcohol_max
     * @param float $extract_min
     * @param float $extract_max
     * @param string $malt
     * @param string $type
     * @param string $filtered
     * @param string $pasteurized
     * @param string $flavored
     * @param string $placeOfBrew
     * @param \PoradnikPiwny\Entities\BeerFamily $family
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @param \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     * @param \PoradnikPiwny\Entities\Country $country
     * @param \PoradnikPiwny\Entities\Region $region
     * @param \PoradnikPiwny\Entities\City $city
     * @throws \PoradnikPiwny\Exception\SearchNotFoundException
     */
    public function searchBeers($uid, $query, $user, $ranking_min, $ranking_max,
                                 $alcohol_min, $alcohol_max, $extract_min, $extract_max,
                                 $malt, $type, $filtered, $pasteurized,
                                 $flavored, $placeOfBrew, $family, $distributor,
                                 $manufacturer, $country, $region, $city)
    {
        /* @var $dc \Bisna\Doctrine\Container  */
        $dc = \Zend_Registry::get('doctrine');
        /* @var $conn \Doctrine\DBAL\Connection */
        $conn = $dc->getConnection('sphinx');  
        
        $r_search = $this->addSearchValues($uid, $query, $user, $ranking_min, $ranking_max,
                                 $alcohol_min, $alcohol_max, $extract_min, $extract_max,
                                 $malt, $type, $filtered, $pasteurized,
                                 $flavored, $placeOfBrew, $family, $distributor,
                                 $manufacturer, $country, $region, $city);
        
        $searchId = $r_search->getId();
        $fields = array();
        
        if(!empty($query)) 
        {
            $sql = 'SELECT @id
                    FROM in_pp_beers, in_pp_beers_ascii, in_delta_pp_beers, in_delta_pp_beers_ascii,'
                    . 'in_pp_beer_translations, in_pp_beer_translations_ascii,' 
                    . 'in_delta_pp_beer_translations, in_delta_pp_beer_translations_ascii'
                    . ' WHERE MATCH(:query)'
                    . ' ORDER BY dateAdded DESC, @weight DESC 
                            OPTION ranker=bm25, max_matches=' . self::SEARCH_MAX_MATCHES . ',
                                field_weights=(name=' . self::FIELD_NAME_WEIGHT . ',
                                     description=' . self::FIELD_DESCRIPTION_WEIGHT . ')';

            $result = $conn->executeQuery($sql, array('query' => $query))->fetchAll();

            foreach($result as $k => $v)
            {
                $fields[] = $v['@id'];
            }        

            if(empty($fields))
            {
                throw new \PoradnikPiwny\Exception\SearchNotFoundException();
            }
        }
        
        $where = array(); 
        $params = array();
        $joins = array();

        $ranking_min = floatval($ranking_min);
        if(!empty($ranking_min))
        {
            $where[] = 'b.bee_rankingavg >= :rankingavg_min';
            $params['rankingavg_min'] = sprintf("%01.2f", $ranking_min);
        }

        $ranking_max = floatval($ranking_max);
        if(!empty($ranking_max))
        {
            $where[] = 'b.bee_rankingavg <= :rankingavg_max';
            $params['rankingavg_max'] = sprintf("%01.2f", $ranking_max);
        }
        
        if($manufacturer != null) {
            $where[] = 'bm.beemk_1_id = :manufacturer_id';
            $params['manufacturer_id'] = $manufacturer->getId();
            $joins[] = 'LEFT JOIN beer_manufacturers bm ON b.beem_3_id = bm.beemk_1_id';
            
            if($distributor != null) {
                $where[] = 'bd.beedk_1_id = :distributor_id';
                $params['distributor_id'] = $distributor->getId();
                $joins[] = 'LEFT JOIN beer_distributors bd ON bd.beedk_1_id = bm.beed_1_id';
            }
        }

        $alcohol_min = floatval($alcohol_min);
        if(!empty($alcohol_min))
        {
            $where[] = 'b.bee_alcohol >= :alcohol_min';
            $params['alcohol_min'] = sprintf("%01.1f", $alcohol_min);
        }

        $alcohol_max = floatval($alcohol_max);
        if(!empty($alcohol_max))
        {
            $where[] = 'b.bee_alcohol <= :alcohol_max';
            $params['alcohol_max'] = sprintf("%01.1f", $alcohol_max);
        }
        
        $extract_min = floatval($extract_min);
        if(!empty($extract_min))
        {
            $where[] = 'b.bee_extract >= :extract_min';
            $params['extract_min'] = sprintf("%01.1f", $extract_min);
        }

        $extract_max = floatval($extract_max);
        if(!empty($extract_max))
        {
            $where[] = 'b.bee_extract <= :extract_max';
            $params['extract_max'] = sprintf("%01.1f", $extract_max);
        }

        if($malt != null && !empty($malt)) {
            $where[] = 'b.bee_malt = :malt';
            $params['malt'] = intval($malt);
        }

        if($type != null && !empty($type)) {
            $where[] = 'b.bee_type = :type';
            $params['type'] = intval($type);
        }

        if($country != null && !empty($country)) {
            $where[] = 'c.couk_1_id = :country_id';
            $params['country_id'] = intval($country);
            $joins[] = 'LEFT JOIN countries co ON b.cou_4_id = co.couk_1_id';
        }

        if($region != null && !empty($region)) {
            $where[] = 'r.regk_1_id = :region_id';
            $params['region_id'] = intval($region);
            $joins[] = 'LEFT JOIN regions r ON b.reg_3_id = r.regk_1_id';
        }

        if($city != null && !empty($city)) {
            $where[] = 'c.citk_1_id = :city_id';
            $params['city_id'] = intval($city);
            $joins[] = 'LEFT JOIN cities c ON b.cit_2_id = c.citk_1_id';
        }

        if($flavored != null 
                && !empty($flavored) 
                && $flavored != Beer::SMAKOWE_NIEWIEM) {
            $where[] = 'b.bee_flavored = :flavored';
            $params['flavored'] = intval($flavored);
        }
        
        if($filtered != null 
                && !empty($filtered) 
                && $filtered != Beer::FILTOWANE_NIEWIEM) {
            $where[] = 'b.bee_filtered = :filtered';
            $params['filtered'] = intval($filtered);
        }                

        if($pasteurized != null 
                && !empty($pasteurized) 
                && $pasteurized != Beer::PASTERYZOWANE_NIEWIEM) {
            $where[] = 'b.bee_pasteurized = :pasteurized';
            $params['pasteurized'] = intval($pasteurized);
        }

        if($placeOfBrew != null && !empty($placeOfBrew)) {
            $where[] = 'b.bee_placeofbrew = :placeofbrew';
            $params['placeofbrew'] = intval($placeOfBrew);
        }

        if($family != null && !empty($family)) {
            $where[] = 'bf.beefk_1_id = :beerfamily_id';
            $params['beerfamily_id'] = intval($family);
            $joins[] = 'LEFT JOIN beer_families bf ON b.beef_1_id = bf.beefk_1_id';
        }
        
        $r_fields = array();
        
        $sqlConn = $this->_em->getConnection();
        $sql = 'SELECT b.beek_1_id as id FROM beers as b '
                . implode(' ', $joins);
        
        if(!empty($fields)) {
            $where[] = 'b.beek_1_id IN (' . implode(',', $fields) . ')';
        }
        
        if(!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);  
        }
        
        $sql .= ' LIMIT ' . self::SEARCH_MAX_MATCHES;
        
        $resultSQL = $sqlConn->executeQuery($sql, $params)->fetchAll();
        foreach($resultSQL as $k => $v)
        {
            $r_fields[] = '(' . $searchId . ',' . $v['id'] . ')';
        }

        if(empty($r_fields))
        {
            throw new \PoradnikPiwny\Exception\SearchNotFoundException();
        }

        $sql = 'INSERT INTO beer_search_connections (bees_1_id, bee_2_id) VALUES '
                . implode(',', $r_fields);
        $sqlConn->executeQuery($sql);
    }
    
        
    /**
     * Zwraca wyniki wyszukiwania
     * 
     * @param array $options
     * @param string $uid
     * @param \PoradnikPiwny\Entities\User $user
     * @return \Zend_Paginator
     */
    public function getSearchPaginator($options, $uid, User $user = null) 
    {   
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bsc', 'b', 'bm', 'bd', 'br', 'bs'))
            ->from('\PoradnikPiwny\Entities\BeerSearchConnection', 'bsc')
            ->leftJoin('bsc.beerSearch', 'bs')
            ->leftJoin('bsc.beer', 'b')
            ->leftJoin('b.manufacturer', 'bm')
            ->leftJoin('bm.distributor', 'bd')
            ->leftJoin('b.beerRankings','br','WITH','br.user = :user')
            ->leftJoin('b.country', 'c')
            ->leftJoin('b.region', 'r')
            ->leftJoin('b.city', 'cit')
            ->setParameter('user', $user)
            ->where('bs.uid=:uid')
            ->setParameter('uid', $uid);
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($options['page']);
        $paginator->setItemCountPerPage($options['items']);
        
        return $paginator;
    }
}
