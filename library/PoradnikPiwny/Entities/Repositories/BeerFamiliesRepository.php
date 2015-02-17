<?php

namespace PoradnikPiwny\Entities\Repositories;

use PoradnikPiwny\Entities\BeerFamily;

class BeerFamiliesRepository extends AbstractPaginatorRepository
{   
    private static $_db_orders = array(
        'family_name' => 'bf.name'
    ); 
    
    /* ---------------------------------------------------------------------- */
    
    public function getAvailableItems() {
        return array(10, 20, 30, 50);
    }

    public function getAvailableOrders() {
        return array(
            'family_name'
        );
    }

    public function getDefaultOrders() {
        return array(
            'family_name'
        );
    }
     
    public function getOptionsPaginatorName()
    {
        return 'beer_family_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'family_name';
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
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
        $qb->select(array('bf'))
            ->from('\PoradnikPiwny\Entities\BeerFamily', 'bf')
            ->orderBy($dbOrder, ($options['desc'] == null) ? 'ASC' : 'DESC');
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($options['page']);
        $paginator->setItemCountPerPage($options['items']);
        
        return $paginator;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerFamily $beerfamily
     * @return array
     */
    public function getSortedParents($beerfamily = null)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bf'))
            ->from('\PoradnikPiwny\Entities\BeerFamily', 'bf')
            ->orderBy('bf.name');
        
        if($beerfamily != null)
        {
            $qb->where('bf.id != :id')
               ->setParameter('id', $beerfamily->getId());
        }
        
        $rows = array();       
        foreach($qb->getQuery()->getResult() as $row)
        {
            $rows[$row->getId()] = $row;
        }
        
        return $rows;
    }
    
    /**
     * @param string $name
     * @param array $parents
     */
    public function addChild($name, $parents)
    {
        $beerfamily = new BeerFamily($name);
        
        foreach($parents as $parent)
        {
            $beerfamily->addParent($parent);
        }
        
        $this->_em->persist($beerfamily);
        $this->_em->flush();
    }

    /**
     * @param \PoradnikPiwny\Entities\BeerFamily $beerfamily
     * @param string $name
     * @param array $parents
     */
    public function editChild($beerfamily, $name, $parents)
    {
        $beerfamily->setName($name);
        
        foreach($beerfamily->getParents() as $parent)
        {
            $id = $parent->getId();
            if(isset($parents[$id])) {
               unset($parents[$id]); 
            } else {
                $beerfamily->removeParent($parent);
            }
        }
        
        foreach($parents as $parent)
        {
            $beerfamily->addParent($parent);
        }
        
        $this->_em->persist($beerfamily);
        $this->_em->flush();      
    }
    
    /**
     * Zwraca rodzinę piwa na podstawie id 
     *  
     * @param int $id
     * @param boolean $forceNull
     * @return \PoradnikPiwny\Entities\BeerFamily
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerFamilyNotFoundException
     */
    public function getFamilyById($id, $forceNull = false)
    {
        if(!\Zend_Validate::is($id, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        $beerfamily = $this->_em->find('\PoradnikPiwny\Entities\BeerFamily', intval($id));
        
        if(!$beerfamily)
        {
            throw new \PoradnikPiwny\Exception\BeerFamilyNotFoundException();
        }
        
        return $beerfamily;
    }
}