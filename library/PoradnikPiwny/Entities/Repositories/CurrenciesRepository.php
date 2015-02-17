<?php

namespace PoradnikPiwny\Entities\Repositories;

use PoradnikPiwny\Entities\Currency,
    PoradnikPiwny\Entities\CurrencyExchange,
    Doctrine\ORM\NoResultException;

class CurrenciesRepository extends AbstractPaginatorRepository
{   
    private static $_db_orders = array(
        'currency_name' => 'c.name',
        'currency_shortcut' => 'c.shortcut',
        'currency_symbol' => 'c.symbol'
    ); 
    
    /* ---------------------------------------------------------------------- */
    
    public function getAvailableItems() {
        return array(10, 20, 30, 50);
    }

    public function getAvailableOrders() {
        return array(
            'currency_name',
            'currency_shortcut',
            'currency_symbol'
        );
    }

    public function getDefaultOrders() {
        return array(
            'currency_name',
            'currency_shortcut',
            'currency_symbol'
        );
    }
     
    public function getOptionsPaginatorName()
    {
        return 'currency_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'currency_name';
    }
    
    /* ---------------------------------------------------------------------- */
       
    /**
     * Zwraca paginator dla walut
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
        $qb->select('c')
            ->from('\PoradnikPiwny\Entities\Currency', 'c')
            ->orderBy($dbOrder, ($options['desc'] == null) ? 'ASC' : 'DESC');
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($options['page']);
        $paginator->setItemCountPerPage($options['items']);
        
        return $paginator;
    }
    
    /**
     * Dodaje nową walutę
     * 
     * @param string $name
     * @param string $symbol
     */
    public function addCurrency($name, $symbol)
    {
        $cur = new Currency($name, $symbol);
        
        $this->_em->persist($cur);
        
        $curs = $this->getCurrieniesExcludeCurrency($cur);
        
        foreach($curs as $curEx)
        {
            $ex = new CurrencyExchange(null, $cur, $curEx);
            $this->_em->persist($ex);
            
            $ex = new CurrencyExchange(null, $curEx, $cur);
            $this->_em->persist($ex);
        }
        
        $this->_em->flush();
    }
    
    /**
     * Edytuje walutę
     * 
     * @param \PoradnikPiwny\Entities\Currency $cur
     * @param string $name
     * @param string $symbol
     */
    public function editCurrency(Currency $cur, $name, $symbol)
    {
        $cur->setName($name);
        $cur->setSymbol($symbol);
        
        $this->_em->persist($cur);
        $this->_em->flush();
    }
    
    /**
     * Usuwa walutę
     * 
     * @param \PoradnikPiwny\Entities\Currency $cur
     */
    public function removeCurrency(Currency $cur)
    {
        $this->_em->remove($cur);
        $this->_em->flush();
    }
    
    /**
     * Zwraca walutę na podstawie id
     * 
     * @param int $id
     * @param boolean $forceNull
     * @return \PoradnikPiwny\Entities\Currency
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\CurrencyNotFoundException
     */
    public function getCurrencyById($id, $forceNull = false)
    {
        if($id == null)
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        $cur = $this->_em->find('\PoradnikPiwny\Entities\Currency', intval($id));
        
        if(!$cur)
        {
            throw new \PoradnikPiwny\Exception\CurrencyNotFoundException();
        }
        
        return $cur;
    }
    
    /**
     * Zwraca paginator dla przelicznika walut
     * 
     * @param \PoradnikPiwny\Entities\Currency $cur
     * @param int $offset
     * @param int $itemCountPerPage
     * @param string $order
     * @param boolean $desc
     * @return \Zend_Paginator
     */
    public function getCurrencyExchangesPaginator(Currency $cur,$offset, $itemCountPerPage,
                                                     $order, $desc = false)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('cex', 'c', 'c2'))
            ->from('\PoradnikPiwny\Entities\CurrencyExchange', 'cex')
            ->leftJoin('cex.currency', 'c2')
            ->leftJoin('cex.currencyExchanged', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $cur->getId())
            ->orderBy($order, $desc ? 'ASC' : 'DESC');
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($offset);
        $paginator->setItemCountPerPage($itemCountPerPage);
        
        return $paginator;
    }
    
    /**
     * Zwraca listę wszystkich walut
     * za wyjątkiem waluty podanej jako parametr funkcji
     * 
     * @param \PoradnikPiwny\Entities\Currency $cur
     * 
     * @return array
     */
    public function getCurrieniesExcludeCurrency(Currency $cur) 
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
            ->from('\PoradnikPiwny\Entities\Currency', 'c')
            ->where('c.id != :id')
            ->setParameter('id', $cur->getId());
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Zwraca przelicznik waluty na podstawie id
     * 
     * @param int $id
     * @param boolean $forceNull
     * @return \PoradnikPiwny\Entities\CurrencyExchange
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\CurrencyExchangeNotFoundException
     */
    public function getCurrencyExchangeById($id, $forceNull = false)
    {
        if(!\Zend_Validate::is($id, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        $ex = $this->_em->find('\PoradnikPiwny\Entities\CurrencyExchange', intval($id));
        
        if(!$ex)
        {
            throw new \PoradnikPiwny\Exception\CurrencyExchangeNotFoundException();
        }
        
        return $ex;
    }
    
    /**
     * Zwraca przelicznik na podstawie dwóch walut
     * 
     * @param \PoradnikPiwny\Entities\Currency $cur
     * @param \PoradnikPiwny\Entities\Currency $curEx
     * 
     * @return \PoradnikPiwny\Entities\CurrencyExchange
     */
    public function getCurrencyExchangeByCurrencies(Currency $cur, Currency $curEx)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ex')
            ->from('\PoradnikPiwny\Entities\CurrencyExchange', 'ex')
            ->where('ex.currency = :currency')
            ->andWhere('ex.currencyExchanged = :currencyExchanged')
            ->setParameters(array(
                'currency' => $cur,
                'currencyExchanged' => $curEx
            ));
        
        try {
            $result =$qb->getQuery()->getSingleResult();
        } catch(NoResultException $ex) {
            return null;
        }
        
        return $result;
    }
    
    /**
     * Edytuje przelicznik waluty
     * 
     * @param \PoradnikPiwny\Entities\CurrencyExchange $ex
     * @param float $multiplier
     */
    public function editCurrencyExchange(CurrencyExchange $ex, $multiplier)
    {
        $ex->setMultiplier($multiplier);
        
        $this->_em->persist($ex);
        
        $curEx = $ex->getCurrencyExchanged();
        $cur = $ex->getCurrency();
        
        $ex2 = $this->getCurrencyExchangeByCurrencies($curEx, $cur);
        $multiplier2 = round(1/$multiplier, 6);
        
        if($ex2 == null) {
           $ex2 = new CurrencyExchange($multiplier2, $curEx, $cur);
        } else {
           $ex2->setMultiplier($multiplier2); 
        }
        
        $this->_em->persist($ex2);
        $this->_em->flush();
    }
    
    /**
     * @return array
     */
    public function getSortedCurrencies()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('c'))
            ->from('\PoradnikPiwny\Entities\Currency', 'c')
            ->orderBy('c.name');
        
        $rows = array();       
        foreach($qb->getQuery()->getResult() as $row)
        {
            $rows[$row->getId()] = $row;
        }
        
        return $rows;
    }
}
