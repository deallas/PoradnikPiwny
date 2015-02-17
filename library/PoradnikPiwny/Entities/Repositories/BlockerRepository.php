<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\NoResultException,
    PoradnikPiwny\Entities\BlockerRule,
    PoradnikPiwny\Entities\BlockerAttempt,
    WS\Tool;

class BlockerRepository extends AbstractPaginatorRepository
{   
    private static $_db_orders = array(
        'rule_priority' => 'b.priority',
        'rule_ip' => 'b.ip',
        'resgroup_name' => 'r.name',
        'rule_dateCreated' => 'b.dateCreated',
        'rule_dateExpired' => 'b.dateExpired'
    );
    
    /**
     * @var \PoradnikPiwny\Entities\Repositories\AclRepository
     */
    private $rAcl;
    
    /* ---------------------------------------------------------------------- */
    
    public function getAvailableItems() {
        return array(10, 20, 30, 50);
    }

    public function getAvailableOrders() {
        return array(
            'rule_priority',
            'rule_ip',
            'resgroup_name',
            'rule_dateCreated',
            'rule_dateExpired'
        );
    }

    public function getDefaultOrders() {
        return array(
            'rule_priority',
            'rule_ip',
            'resgroup_name',
            'rule_dateCreated',
            'rule_dateExpired'
        );
    }
    
    public function getOptionsPaginatorName()
    {
        return 'blocker_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'rule_priority';
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * 
     * @param string $resource
     * @param string|null $ip
     * @return \PoradnikPiwny\Entities\BlockerRule
     */
    public function getRule($resource, $ip = null)
    {
        if($ip == null)
            $ip = Tool::getRealIp();
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select('bl')
            ->from('\PoradnikPiwny\Entities\BlockerRule', 'bl')
            ->where('bl.resource=:resource')
            ->andWhere('bl.ip=:ip')
            ->setParameter('ip', $ip)
            ->setParameter('resource', $resource);
        
        try {
            $result = $qb->getQuery()->getSingleResult();
            return $result;
        } catch(NoResultException $ex) {
            return null;
        }
    }
    
    /**
     * Dodanie nowej reguły
     * 
     * @param string $ip
     * @param \Zend_Date $dateExpired
     * @param int $priority
     * @param string $message
     * @param \PoradnikPiwny\Entities\AclResgroup $aclResgroup
     */
    public function addRule($ip = null, \Zend_Date $dateExpired = null, $priority = null,
                            $message = null, $aclResgroupId = null)
    {
        if($ip == null) {
            $ip = Tool::getRealIp();
        }
        
        $aclResgroup = null;  
        if($aclResgroupId != null && $aclResgroupId != 0)
        {
            $this->rAcl = $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole');
            $aclResgroup = $this->rAcl->getResgroupById($aclResgroupId);
        }
        
        $blocker = new BlockerRule($ip, $dateExpired, $priority, $message, $aclResgroup);
        
        $this->_em->persist($blocker);
        $this->_em->flush();
    }
    
    public function addRuleByResgroupName($ip = null, \Zend_Date $dateExpired = null, $priority = null,
                            $message = null, $aclResgroupName = null)
    {
        $this->rAcl = $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole');
        $aclResgroupId = $this->rAcl->getResgroupByName($aclResgroupName);
        
        $this->addRule($ip, $dateExpired, $priority, $message, $aclResgroupId);
    }
    
    /**
     * Edytowanie istniejącej reguły
     * 
     * @param \PoradnikPiwny\Entities\BlockerRule $rule
     * @param string $ip
     * @param \Zend_Date $dateExpired
     * @param int $priority
     * @param string $message
     * @param int $aclResgroupId
     */
    public function editRule(BlockerRule $rule, $ip = null, \Zend_Date $dateExpired = null,
                            $priority = null, $message = null, $aclResgroupId = null)
    {
        $rule->setIp($ip);
         
        if($aclResgroupId != null && $aclResgroupId != 0)
        {
            $this->rAcl = $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole');
            $rule->setAclResgroup($this->rAcl->getResgroupById($aclResgroupId));
        } else {
            $rule->setAclResgroup(null);
        }
        
        $rule->setPriority($priority);
        $rule->setMessage($message);

        if($dateExpired == null) {
            $rule->setDateExpired(null);
        } else {
            $rule->setDateExpired($dateExpired);
        }
        
        $this->_em->persist($rule);
        $this->_em->flush();
    }
    
    /**
     * Usunięcie istniejącej reguły
     * 
     * @param \PoradnikPiwny\Entities\BlockerRule $rule
     */
    public function removeRule(BlockerRule $rule)
    {
        $this->_em->remove($rule);
        $this->_em->flush();
    }

    /**
     * @param string $resgroupName
     * @param string $ip
     * @return \PoradnikPiwny\Entities\BlockerRule
     */
    public function getNotExpiredRule($resgroupName, $ip = null)
    {
        if($ip == null)
            $ip = Tool::getRealIp();
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select('bl')
            ->from('\PoradnikPiwny\Entities\BlockerRule', 'bl')
            ->leftJoin('bl.aclResgroup', 'ar')
            ->where('(ar.name=:resgroupName) OR (bl.aclResgroup IS NULL)')
            ->andWhere('bl.ip=:ip')
            ->andWhere('(bl.dateExpired >= :dateExpired OR bl.dateExpired IS NULL)')
            ->orderBy('bl.priority')
            ->setParameter('ip', $ip)
            ->setParameter('resgroupName', $resgroupName)
            ->setParameter('dateExpired', \Zend_Date::now()->getIso());
        
        try {
            $result = $qb->getQuery()->getSingleResult();
            return $result;
        } catch(NoResultException $ex) {
            return null;
        }
    }
    
    /**
     * Zwraca paginator dla zasad
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
        $qb->select('b', 'r')
            ->from('\PoradnikPiwny\Entities\BlockerRule', 'b')
            ->leftJoin('b.aclResgroup', 'r')
            ->orderBy($dbOrder, ($options['desc'] == null) ? 'ASC' : 'DESC');
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($options['page']);
        $paginator->setItemCountPerPage($options['items']);
        
        return $paginator;
    }
    
    /**
     * Zwraca największy priorytet dla zdefiniowanych zasad
     * 
     * @return int
     */
    /*public function getMaxPriorityRule()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from('\PoradnikPiwny\Entities\BlockerRule', 'b')
            ->orderBy('b.priority', 'DESC');
        
        try {
            $result = $qb->getQuery()->getSingleResult();
            return $result->getPriority();
        } catch(NoResultException $ex) {
            return 0;
        }
    }*/
    
    /**
     * Sprawdza czy pola ip wraz z przypisaną do niego grupą istnieją w bazie
     * 
     * @param string $ip
     * @param int $resgroupId
     * @param int $excludeId
     * @return boolean
     */
    public function isIpResgroupUnique($ip, $resgroupId, $excludeId = null)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b')
            ->from('\PoradnikPiwny\Entities\BlockerRule', 'b')
            ->leftJoin('b.aclResgroup', 'r')
            ->where('b.ip = :ip')
            ->andWhere('r.id = :resgroupId')
            ->setParameter('ip', $ip)
            ->setParameter('resgroupId', intval($resgroupId));
        
        if($excludeId != null) {
            $qb->andWhere('b.id != :id')
               ->setParameter('id', $excludeId);
        }
        
        try {
            $qb->getQuery()->getSingleResult();
            return true;
        } catch(NoResultException $ex) {
            return false;
        }        
    }

    /**
     * Zwraca zasadę modułu blokującego na podstawie id
     * 
     * @param int $id
     * @param boolean $forceNull
     * @return \PoradnikPiwny\Entities\BlockerRule
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BlockerRuleNotFoundException
     */
    public function getRuleById($id, $forceNull = false)
    {
        if(!\Zend_Validate::is($id, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }            
        }

        $blocker = $this->_em->find('\PoradnikPiwny\Entities\BlockerRule', intval($id));
        
        if(!$blocker)
        {
            throw new \PoradnikPiwny\Exception\BlockerRuleNotFoundException();
        }
        
        return $blocker; 
    }
    
    /*------------------------------------------------------------------*/
    
    /**
     * Dodanie nowego licznika prób
     * 
     * @param string $resgrpName
     * @param \Zend_Date $dateExpired
     * @param string $ip 
     */
    public function appendAttempt($resgrpName, $dateExpired, $ip = null)
    {
        if($ip == null) {
            $ip = Tool::getRealIp();
        }
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ba')
           ->from('\PoradnikPiwny\Entities\BlockerAttempt', 'ba')
           ->where('ba.ip=:ip')
           ->leftJoin('ba.aclResgroup', 'res')
           ->andWhere('res.name=:resgrp')
           ->setParameter('ip', $ip)
           ->setParameter('resgrp', $resgrpName);  
        
        try {
            $result = $qb->getQuery()->getSingleResult();
            $result->incrementAttempts();
        } catch(NoResultException $ex) {            
            $resgrp = $this->_em->getRepository('\PoradnikPiwny\Entities\AclResgroup')
                                ->getResgroupByName($resgrpName);
            
            $result = new BlockerAttempt($ip, $dateExpired, 1, $resgrp);
        }
        
        $this->_em->persist($result);
        $this->_em->flush();      	
    }

    /**
     * Usunięcie licznika
     * 
     * @param string $resgrpName
     * @param string $ip
     */
    public function removeAttempt($resgrpName, $ip = null)
    {
        if($ip == null) {
            $ip = Tool::getRealIp();
        }
        $qb = $this->_em->createQueryBuilder();
        $qb->delete('\PoradnikPiwny\Entities\BlockerAttempt', 'ba');
        $qb->where('ba.ip = :ip')
           ->setParameter('ip', $ip);
        
        if($resgrpName != null) {
            $resgrp = $this->_em->getRepository('\PoradnikPiwny\Entities\AclResgroup')
                                ->getResgroupByName($resgrpName);
            if($resgrp != null)
            {
                $qb->andWhere('ba.aclResgroup=:resgrp')
                   ->setParameter('resgrp', $resgrp);   
            }
        }
        
        $qb->getQuery()->execute();
        $this->_em->flush();
    }

    /**
     * Usunięcie "przeterminowanych" liczników
     * 
     * @param string $resgrpName
     * @param string $ip 
     */
    public function removeExpiredAttempts($resgrpName = null, $ip = null)
    {   
        $qb = $this->_em->createQueryBuilder()
           ->delete('\PoradnikPiwny\Entities\BlockerAttempt', 'ba')
           ->where('ba.dateExpired <= :dateExpired')
           ->setParameter('dateExpired', \Zend_Date::now()->getIso());

        if($resgrpName != null) {
            $resgrp = $this->_em->getRepository('\PoradnikPiwny\Entities\AclResgroup')
                                ->getResgroupByName($resgrpName);
            if($resgrp != null)
            {
                $qb->andWhere('ba.aclResgroup=:resgrp')
                   ->setParameter('resgrp', $resgrp);   
            }
        }
        if($ip != null) {
            $qb->andWhere('ba.ip = :ip')
               ->setParameter('ip', $ip);
        }
        $qb->getQuery()->execute();
        $this->_em->flush();
    }

    /**
     * Pobranie liczby prób dla nieprzeterminowanego zasobu
     * 
     * @param string $resgrpName
     * @param string $ip 
     * @return int
     */  
    public function getNotExpiredAttempts($resgrpName, $ip = null)
    {
        if($ip == null) {
            $ip = Tool::getRealIp();
        }
        $qb = $this->_em->createQueryBuilder();
        $qb->select('ba', 'res')
           ->from('\PoradnikPiwny\Entities\BlockerAttempt', 'ba')
           ->where('ba.dateExpired >= :dateExpired')
           ->leftJoin('ba.aclResgroup', 'res')
           ->andWhere('res.name=:resname')
           ->andWhere('ba.ip = :ip')
           ->setParameters(array(
                'dateExpired' => \Zend_Date::now()->getIso(),
                'resname' => $resgrpName,
                'ip' => $ip));
        
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch(NoResultException $ex) {
            return 0;
        }
        
        return $result->getAttempts();
    }
}

