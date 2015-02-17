<?php

namespace PoradnikPiwny\Entities\Repositories;

use PoradnikPiwny\Entities\BeerDistributor;

class BeerDistributorsRepository extends AbstractPaginatorRepository
{   
    private static $_db_orders = array(
        'distributor_name' => 'bp.name'
    ); 
    
    /* ---------------------------------------------------------------------- */
    
    public function getAvailableItems() {
        return array(10, 20, 30, 50);
    }

    public function getAvailableOrders() {
        return array(
            'distributor_name'
        );
    }

    public function getDefaultOrders() {
        return array(
            'distributor_name'
        );
    }
     
    public function getOptionsPaginatorName()
    {
        return 'beer_distributor_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'distributor_name';
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * Zwraca paginator dla dystrybutorów
     * 
     * @param array $options
     * @return \Zend_Paginator
     */
    public function getPaginator($options) 
    {
        if(!isset(self::$_db_orders[$options['order']])) {
            $dbOrder = self::$_db_orders[$this->getDefaultOrder()];
        } else {
            $dbOrder = self::$_db_orders[$options['order']];
        }
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select('bp')
            ->from('\PoradnikPiwny\Entities\BeerDistributor', 'bp')
            ->orderBy($dbOrder, ($options['desc'] == null) ? 'ASC' : 'DESC');
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($options['page']);
        $paginator->setItemCountPerPage($options['items']);
        
        return $paginator;
    }
    
    /**
     * Zwraca posortowanych dystrybutorów
     * 
     * @return array
     */
    public function getNotEmptySortedDistributors()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bp', 'COUNT(bm.id) as cbm'))
            ->from('\PoradnikPiwny\Entities\BeerDistributor', 'bp')
            ->orderBy('bp.name')
            ->leftJoin('bp.beerManufacturers', 'bm')
            ->groupBy('bp.id');
          
        $rows = array();       
        foreach($qb->getQuery()->getResult() as $row)
        {
            if($row['cbm'] == 0) continue;
            $rows[$row[0]->getId()] = $row[0];
        }
        
        return $rows;
    }
    
    /**
     * Dodaje nowego dystrybutora
     * 
     * @param string $name
     */
    public function addDistributor($name)
    {
        $distributor = new BeerDistributor($name);
        
        $this->_em->persist($distributor);
        $this->_em->flush();
    }
    
    /**
     * Edytuje dystrybutora
     * 
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @param string $name
     */
    public function editDistributor(BeerDistributor $distributor, $name)
    {
        $distributor->setName($name);
        
        $this->_em->persist($distributor);
        $this->_em->flush();
    }
    
    /**
     * Usuwa dystrybutora
     * 
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     */
    public function removeDistributor(BeerDistributor $distributor)
    {
        $this->_em->remove($distributor);
        $this->_em->flush();
    }
    
    /**
     * Zwraca dystrybutora na podstawie id
     * 
     * @param int $id
     * @param boolean $forceNull
     * @return \PoradnikPiwny\Entities\BeerDistributor
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\DistributorNotFoundException
     */
    public function getDistributorById($id, $forceNull = false)
    {
        if(!\Zend_Validate::is($id, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        $distributor = $this->_em->find('\PoradnikPiwny\Entities\BeerDistributor', intval($id));
        
        if(!$distributor)
        {
            throw new \PoradnikPiwny\Exception\DistributorNotFoundException();
        }
        
        return $distributor;
    }
}
