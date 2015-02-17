<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\NoResultException,
    PoradnikPiwny\Entities\User,
    PoradnikPiwny\Entities\AclRole,
    PoradnikPiwny\Security;

class AclRepository extends EntityRepository
{   
    const CACHE_IS_ALLOWED_PREFIX = 'acl_is_allowed_';
    const CACHE_IS_CHILD_ID_FOR_USER = 'acl_is_child_id_for_user_';
    
    /** 
     * @var \Zend_Cache_Manager
     */
    private static $_cacheManager = null;
    
    /*------------------------------------------------------------------*/
    
    /**
     * Pobiera grupę zasobów na podstawie id
     * 
     * @param int $id
     * @return \PoradnikPiwny\Entities\AclResgroup
     */
    public function getResgroupById($id)
    {
        return $this->_em->find('\PoradnikPiwny\Entities\AclResgroup', intval($id));      
    }
    
    /**
     * Pobiera grupę zasobów na podstawie nazwy
     * 
     * @param string $resname
     * @return \PoradnikPiwny\Entities\AclResgroup
     */
    public function getResgroupByName($resname)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('resgrp')
           ->from('\PoradnikPiwny\Entities\AclResgroup', 'resgrp')
           ->where('resgrp.name = :resname')
           ->setParameter('resname', $resname);
        
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch(NoResultException $ex) {
            return null;
        }
        
        return $result;
    }
    
    /**
     * Pobranie wszystkich grup zasobów w kolejności alfabetycznej
     * 
     * @return array
     */
    public function getResgroups()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('resgrp')
           ->from('\PoradnikPiwny\Entities\AclResgroup', 'resgrp')
           ->orderBy('resgrp.name');
        
        return $qb->getQuery()->getResult();
    }
    
    /*------------------------------------------------------------------*/
    
    /**
     * Pobiera role na podstawie parametru id
     * 
     * @param int $id
     * @return \PoradnikPiwny\Entities\AclRole
     */
    public function getRoleById($id)
    {
        return $this->_em->find('\PoradnikPiwny\Entities\AclRole', intval($id));
    }
    
    /**
     * Pobiera listę rodziców dla roli użytkownika
     * (pomija domyślną rolę)
     * 
     * @param \PoradnikPiwny\Entities\User $user
     * @return array
     */
    public function getRoleParentsForUser(User $user)
    {
        $roleId = $user->getRole()->getId();
        
        $sql = 'SELECT * FROM getRoleParentsByRoleId(' . $roleId . ')
                    WHERE id != ' . $roleId . ' AND id != ' . Security::ROLE_GUEST . '
                    ORDER BY name';
        
        return $this->_em->getConnection()->executeQuery($sql)->fetchAll();
    }
    
    /**
     * @param \PoradnikPiwny\Entities\AclRole $role
     * @param \PoradnikPiwny\Entities\User $user
     * @return boolean
     */
    public function isChildRoleForUser(AclRole $role, User $user)
    {
        $childRoleId = $role->getId();
        $roleId = $user->getRole()->getId();
        $cache = self::getCacheManager()->getCache('memory');
        $name = self::CACHE_IS_CHILD_ID_FOR_USER . $childRoleId . '_' . $roleId;
        
        if(!($res = $cache->load($name))) {
            $sql = 'SELECT * FROM isChildRoleByRoleId(' . 
                    $childRoleId . ',' . $roleId . ')';

            $res = (bool)$this->_em->getConnection()
                                   ->executeQuery($sql)
                                   ->fetchColumn();
            
            $cache->save($res, $name);
        }
        
        return $res;
    }
    
    /*------------------------------------------------------------------*/
    
    /**
     * @param string $resource
     * @param string $privilege
     * @param int $roleId
     */
    public function isAllowed($resource, $privilege, $roleId = Security::ROLE_GUEST)
    {
        $cache = self::getCacheManager()->getCache('memory');
        $name = self::CACHE_IS_ALLOWED_PREFIX . md5($resource . '_' . $privilege . '_' . $roleId);
        if(!($res = $cache->load($name))) {
            $sql = 'SELECT * FROM isAllowed(:resource, :privilege, :roleId)';
            $con = $this->_em->getConnection();

            $q = $con->executeQuery($sql, array(
                'resource' => $resource,
                'privilege' => $privilege,
                'roleId' => $roleId
            ));

            $res = array((bool)$q->fetchColumn()); // STUPID BUG -> STUPID FIX 
            
            $cache->save($res, $name);
        }

        return $res[0];
    }
    
    /**
     * @return \Zend_Cache_Manager
     */
    private static function getCacheManager()
    {
        if(self::$_cacheManager == null)
        {
            self::$_cacheManager = \Zend_Controller_Front::getInstance()
                                        ->getParam('bootstrap')
                                        ->getResource('cachemanager');
        }
        
        return self::$_cacheManager;
    }
}