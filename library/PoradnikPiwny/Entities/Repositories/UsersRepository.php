<?php

namespace PoradnikPiwny\Entities\Repositories;

use PoradnikPiwny\Security,
    Doctrine\ORM\NoResultException,
    PoradnikPiwny\Entities\User,
    PoradnikPiwny\Entities\UserMeta,
    PoradnikPiwny\Exception as PPException;

class UsersRepository extends AbstractPaginatorRepository
{   
    private static $_db_orders = array(
        'user_visiblename' => 'u.visibleName',
        'user_status' => 'u.status',
        'role_name' => 'r.name'
    );
    
    /* ---------------------------------------------------------------------- */
    
    public function getAvailableItems() {
        return array(10, 20, 30, 50);
    }

    public function getAvailableOrders() {
        return array(
            'user_visiblename',
            'user_status',
            'role_name'
        );
    }

    public function getDefaultOrders() {
        return array(
            'user_visiblename',
            'user_status',
            'role_name'
        );
    }
    
    public function getOptionsPaginatorName()
    {
        return 'user_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'user_visiblename';
    }
    
    /* ---------------------------------------------------------------------- */
       
    /**
     * @var \PoradnikPiwny\Entities\Repositories\AclRepository
     */
    protected $rAcl;
    
    /**
     * Zwraca tablice metadanych dla użytkownika o konkretnym id
     * 
     * @param \PoradnikPiwny\Entities\User $user
     * @return array
     */
    public function getAssocMetadatasByUser(User $user)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('um')
            ->from('\PoradnikPiwny\Entities\UserMeta', 'um')
            ->leftJoin('um.user', 'u')
            ->where('u.id=:userId')
            ->setParameter('userId', $user->getId());
 
        $result = $qb->getQuery()->getResult();
        
        $mds = array();
        foreach($result as $row)
        {
            $mds[$row->getKey()] = $row->getValue();
        }
        
        return $mds;
    }
    
    /**
     * @param string $key
     * @param \PoradnikPiwny\Entities\User $user
     * @return \PoradnikPiwny\Entities\UserMeta
     */
    public function getMetadataByKeyAndUser($key, User $user)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('um')
           ->from('\PoradnikPiwny\Entities\UserMeta', 'um')
           ->leftJoin('um.user', 'u')
           ->where('u.id=:userId')
           ->setParameter('userId', intval($user->getId()))
           ->andWhere('um.key=:key')
           ->setParameter('key', $key);
        
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch(NoResultException $exc) {
            $result = null;
        }
        
        return $result;
    }
    
    /**
     * @param string $key
     * @param \PoradnikPiwny\Entities\User $user
     * @param string|array $value
     */
    public function setMetadataByKeyAndUser($key, User $user, $value)
    {
        $options = $this->getMetadataByKeyAndUser($key, $user);
        if($options == null) {
            $options = new UserMeta($key, $value, $user);
        } else {
            $options->setValue($value);
        }
        
        $this->_em->persist($options);
        $this->_em->flush();
    }
    
    /**
     * @param string $key
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function removeMetadataByKeyAndUser($key, User $user)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->delete('\PoradnikPiwny\Entities\UserMeta', 'um')
           ->where('um.key = :key')
           ->setParameter('key', $key)
           ->andWhere('um.user = :user')
           ->setParameter('user', $user);
        
        $qb->getQuery()->execute();
    }
    
    /**
     * Sprawdza czy podane hasło zgadza się z zapisanym w bazie
     * 
     * @param string $password
     * @param int $id
     * @return boolean
     */
    public function checkPassword($password, $id)
    { 
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from('\PoradnikPiwny\Entities\User', 'u')
            ->where('u.id=:id')
            ->setParameter('id', $id);
        
        try {
            $result = $qb->getQuery()->getSingleResult();
            
            $hash = $result->getPassword();
            $password .= $result->getSalt();
            
            if(Security::getInstance()->createPassword($password) != $hash)
            {
                return false;
            }
        } catch(NoResultException $ex) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Zmienia hasło dla wybranego użytkownika
     * 
     * @param string $password
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function changePassword($password, User $user)
    {
        $user->setPassword(
            Security::getInstance()->createPassword($password . $user->getSalt())
        );
        
        $this->_em->persist($user);
        $this->_em->flush();
    }
    
    /**
     * Zwraca paginator dla użytkowników
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
        $qb->select('u', 'r')
            ->from('\PoradnikPiwny\Entities\User', 'u')
            ->leftJoin('u.role', 'r')
            ->orderBy($dbOrder, ($options['desc'] == null) ? 'ASC' : 'DESC');
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($options['page']);
        $paginator->setItemCountPerPage($options['items']);
        
        return $paginator;
    }
   
    /**
     * Dodaje nowego użytkownika
     * 
     * @param string $username
     * @param string $visibleName
     * @param string $email
     * @param string $password
     * @param int $roleId
     * @param string $status
     * @param string $name
     * @param string $surname
     * @param string $theme
     * @throws \PoradnikPiwny\Exception
     */
    public function addUser($username,
                            $visibleName,
                            $email, 
                            $password,
                            $roleId,
                            $status,
                            $name,
                            $surname,
                            $theme)
    {   
        $this->rAcl = $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole');
        $role = $this->rAcl->getRoleById($roleId);
        
        if(!$role)
        {
            throw new PPException('Rola o identyfikatorze "' . $roleId . '" nie istnieje');
        }
        
        $user = new User($username, $visibleName, $email, $password, $role, $status);
        $user->addMetadata(new UserMeta('name', $name, $user));
        $user->addMetadata(new UserMeta('surname', $surname, $user));
        $user->addMetadata(new UserMeta('theme', $theme, $user));

        $this->_em->persist($user);
        $this->_em->flush();
    }
    
    /**
     * Edytuje wybranego użytkownika
     * 
     * @param \PoradnikPiwny\Entities\User $user
     * @param string $username
     * @param string $visibleName
     * @param string $email
     * @param int $roleId
     * @param string $status
     * @param strine $name
     * @param string $surname
     * @param string $theme
     * @throws \Exception
     */
    public function editUser(User $user,
                            $username,
                            $visibleName,
                            $email,
                            $roleId,
                            $status,
                            $name,
                            $surname,
                            $theme)
    {
        $user->setUsername($username);
        $user->setVisibleName($visibleName);
        $user->setEmail($email);

        if($roleId != null)
        {
            $this->rAcl = $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole');
            $role = $this->rAcl->getRoleById($roleId);

            if(!$role)
            {
                throw new PPException('Rola o identyfikatorze "' . $roleId . '" nie istnieje');
            }
            $user->setRole($role);
        }
        
        if($status != null)
        {
            $user->setStatus($status);
        }
        
        $it = $user->getMetadatas();
        while($it->valid())
        {
            $meta = $it->current();
            $key = $meta->getKey();
            switch($key)
            {
                case 'name':
                    $meta->setValue($name);
                    break;
                case 'surname':
                    $meta->setValue($surname);
                    break;
                case 'theme':
                    $meta->setValue($theme);
                    break;
                default:
                    throw new \Exception('Unknown user parametr "' . $key . '"');
            }
            $it->next();
        }

        $this->_em->persist($user);
        $this->_em->flush();
    }
    
    /**
     * Usuwa użytkownika
     * 
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function removeUser(User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
    
    /**
     * Pobiera użytkownika na podstawie id
     * 
     * @param int $id
     * @param boolean $forceNull
     * @return \PoradnikPiwny\Entities\User
     */
    public function getUserById($id, $forceNull = false)
    {
        if(!\Zend_Validate::is($id, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        $user = $this->_em->find('\PoradnikPiwny\Entities\User', intval($id));
        
        if(!$user)
        {
            throw new \PoradnikPiwny\Exception\UserNotFoundException();
        }
        
        return $user;
    }
}
