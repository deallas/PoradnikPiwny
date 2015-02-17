<?php

namespace PoradnikPiwny;

use WS\Options,
    PoradnikPiwny\Security\Exception as SecurityException,
    WS\Auth\Adapter\Doctrine as DoctrineAdapter,
    PoradnikPiwny\Entities\User;

class Security extends Options
{
    const ACL_PERMISSION_DENIED = 1;
    const ACL_PERMISSION_GRANTED = 2;
    const ACL_RESOURCE_NOT_FOUND = 3;

    const AUTH_REMOVED_ACCOUNT = 11;
    const AUTH_EXPIRED_SESSION = 12;
    const AUTH_STOLEN_SESSION = 13;
    const AUTH_BANNED_ACCOUNT = 14;
    const AUTH_INACTIVE_ACCOUNT = 15;

    const BLOCKER_PERMISSION_DENIED = 21;

    const UNKNOWN_EXCEPTION = -1;
    
    const ROLE_GUEST = 1;
    const ROLE_USER = 2;
    
    const ROLE_PIWOSZ = 3; 
    const ROLE_ADMIN = 4;
    const ROLE_TESTER = 5;
    const ROLE_PROGRAMMER = 6;
    
    const RESOURCE_GROUP_DEFAULT = 'default';
    const RESOURCE_GROUP_ADMIN = 'admin';
    const RESOURCE_GROUP_REST = 'rest';
        
    /**
     * @var array
     */
    protected $errorPage = null;

    /**
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    /**
     * @var \Zend_Auth
     */
    protected $auth;
    
    /**
     * @var \PoradnikPiwny\Security\Acl
     */
    protected $acl = null;
    
    /**
     * @var \PoradnikPiwny\Entities\User
     */
    protected $user;
    
    /**
     * @var \PoradnikPiwny\Security
     */
    protected static $instance = false; 
    
    /*------------------------------------------------------------------*/
    
    /**
     * Pobranie obiektu singleton
     * 
     * @return \PoradnikPiwny\Security
     */    
    public static function getInstance()
    {
        if(self::$instance === false)
        {
            self::$instance = new Security();
        }
        
        return self::$instance;
    }
    
    /**
     * Przypisanie klasie obiektu typu singleton
     * 
     * @param \PoradnikPiwny\Security $instance
     */    
    public static function setInstance(Security $instance)
    {
        self::$instance = $instance;
    }
    
    /*------------------------------------------------------------------*/
    
    /**
     * Funkcja uruchamiana przy inicjowaniu komponentu
     * 
     * @throws \PoradnikPiwny\Security\Exception
     */
    public function init()
    {
    	if($this->isInitialized) {
            return;
        }
        
        $this->isInitialized = true;
        
        $errorPage = $this->getOption('error_page');
        if($errorPage)
        {
            $this->setErrorPage($errorPage);
        }

        $this->em = \Zend_Registry::get('doctrine')->getEntityManager();
        $this->auth = \Zend_Auth::getInstance();

        \Zend_Session::setOptions(array(
            'save_path' => TMP_PATH,
            'use_only_cookies' => true,
            'remember_me_seconds' => 60*60*24*7,
            'cookie_httponly' => true
        ));

        try {
            \Zend_Session::start();
        } catch(\Zend_Session_Exception $e) {
            \Zend_Session::regenerateId(false);
            throw new SecurityException(null, Security::AUTH_STOLEN_SESSION);
        }

        if ($this->auth->hasIdentity()) {
            $id = $this->auth->getIdentity()->getId(); 
            try {
                $user = $this->em->getRepository('\PoradnikPiwny\Entities\User')->getUserById($id);
            } catch(\PoradnikPiwny\Exception\UserNotFoundException $exc) {
                \Zend_Auth::getInstance()->clearIdentity();
                throw new SecurityException(null, Security::AUTH_REMOVED_ACCOUNT);
            }
            
            $this->_checkStatus($user->getStatus());
            $this->user = $user;
        }
    }
    
    /*------------------------------------------------------------------*/

    /**
     * Przypisanie strony błędu w razie pojawienia się braku dostępu
     * 
     * @param array $errorPage
     */
    public function setErrorPage(array $errorPage = array())
    {
        if(isset($errorPage['module'])) { 
            $this->errorPage['module'] = $errorPage['module'];
        } else {
            $this->errorPage['module'] = 'default';
        }
            
        if(isset($errorPage['controller'])) {
            $this->errorPage['controller'] = $errorPage['controller'];
        } else {
            $this->errorPage['controller'] = 'index';
        }
            
        if(isset($errorPage['action'])) {
            $this->errorPage['action'] = $errorPage['action'];
        } else {
            $this->errorPage['action'] = 'index';	
        }
    }

    /**
     * Pobranie danych o lokalizacji strony błędu
     * 
     * @return bool|array
     */
    public function getErrorPage()
    {
        return ($this->errorPage != null) ? $this->errorPage : null;
    }

    /**
     * Sprawdza czy zdefiniowano stronę błędu
     * 
     * @return bool
     */
    public function hasErrorPage()
    {
        return ($this->errorPage == null) ? false : true;
    }
    
    /*------------------------------------------------------------------*/
    
    /**
     * Wygenerowanie hashu do podanego hasła
     * 
     * @param string $password
     * @return string
     */
    public function createPassword($password)
    {
        $opts = $this->getOption('hash');
        if(isset($opts['type'])) {
            if(isset($opts['salt'])) {  
                return hash($opts['type'], $opts['salt'] . $password);
            } else {
                return hash($opts['type'], $password);
            }
        } else {
            return $password;
        }
    }
    
    /**
     * @param string $email
     * @param string $password
     * @param boolean $rememberMe
     * @param int $inheritRole
     * @return boolean
     */
    public function login($email, $password, $rememberMe = true, $inheritRole = null)
    {
        $adapter = new DoctrineAdapter($this->em, '\PoradnikPiwny\Entities\User');
        $adapter->setIdentityColumn(array('email', 'username'));
        $adapter->setCredentialColumn('password');
        $adapter->setSaltColumn('salt');
        $adapter->setIdentity($email);
        $adapter->setInheritRole($inheritRole);
        $adapter->setCredential($password);

        $result = $this->auth->authenticate($adapter);

        if ($result->isValid()) {
            $data = $adapter->getResultRowObject();
            $this->_checkStatus($data->getStatus());
            
            $this->auth->getStorage()->write($data);
            $this->user = $data;
            
            if($rememberMe) {
                \Zend_Session::rememberMe();
            }
            
            return true;
        } else {
            return false;
        }

        return true;
    }

    public function logout()
    {
        \Zend_Auth::getInstance()->clearIdentity();
        $this->user = null;
    }
    
    /*------------------------------------------------------------------*/
    
    /**
     * @param string $resource
     * @param string $privilege
     * @param int $role
     * @return type
     */
    public function isAllowed($resource, $privilege, $role = null)
    {
        if($role == null)
        {
            $role = $this->getRole();
        }
        
        $rResource = $this->em->getRepository('\PoradnikPiwny\Entities\AclResource');
        if($rResource->isAllowed($resource, $privilege, $role))
        {
            return Security::ACL_PERMISSION_GRANTED;
        }
        
        return Security::ACL_PERMISSION_DENIED;
    }

    /**
     * @return \PoradnikPiwny\Entities\User
     */
    public function getUser()
    {
        if($this->user == null) return false;
        
        return $this->user;
    }

    /**
     * Zwraca identyfikator roli użytkownika
     * 
     * @return int
     */
    public function getRole()
    {
        if($this->user == null) return self::ROLE_GUEST;
        
        return $this->getUser()->getRole()->getId();        
    }
    
    /**
     * @return \PoradnikPiwny\Security\Acl
     */
    public function getAcl()
    {
        if($this->acl == null) {
             $this->acl = new \PoradnikPiwny\Security\Acl();
        }
        
        return $this->acl;
    }
    
    /**
     * Sprawdza czy posiadamy domyślną rolę (GUEST)
     * 
     * @return boolean
     */
    public function hasDefaultRole()
    {  
        return $this->getRole() == self::ROLE_GUEST;
    }
    
    /**
     * @param string $status
     * @throws SecurityException
     */
    private function _checkStatus($status)
    {
        if($status == User::STATUS_BANNED) {
            \Zend_Auth::getInstance()->clearIdentity();
            throw new SecurityException(null, Security::AUTH_BANNED_ACCOUNT);
        }
        
        if($status == User::STATUS_INACTIVE) {
            \Zend_Auth::getInstance()->clearIdentity();
            throw new SecurityException(null, Security::AUTH_INACTIVE_ACCOUNT);
        }
    }
}