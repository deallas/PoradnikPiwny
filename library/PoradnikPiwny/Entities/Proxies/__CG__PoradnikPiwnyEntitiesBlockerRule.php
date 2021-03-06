<?php

namespace PoradnikPiwny\Entities\Proxies\__CG__\PoradnikPiwny\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class BlockerRule extends \PoradnikPiwny\Entities\BlockerRule implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setId($id)
    {
        $this->__load();
        return parent::setId($id);
    }

    public function getIp()
    {
        $this->__load();
        return parent::getIp();
    }

    public function setIp($ip)
    {
        $this->__load();
        return parent::setIp($ip);
    }

    public function getDateCreated()
    {
        $this->__load();
        return parent::getDateCreated();
    }

    public function setDateCreated($dateCreated)
    {
        $this->__load();
        return parent::setDateCreated($dateCreated);
    }

    public function getDateExpired()
    {
        $this->__load();
        return parent::getDateExpired();
    }

    public function setDateExpired($dateExpired)
    {
        $this->__load();
        return parent::setDateExpired($dateExpired);
    }

    public function getMessage()
    {
        $this->__load();
        return parent::getMessage();
    }

    public function setMessage($message)
    {
        $this->__load();
        return parent::setMessage($message);
    }

    public function getPriority()
    {
        $this->__load();
        return parent::getPriority();
    }

    public function setPriority($priority)
    {
        $this->__load();
        return parent::setPriority($priority);
    }

    public function getAclResgroup()
    {
        $this->__load();
        return parent::getAclResgroup();
    }

    public function setAclResgroup($aclResgroup)
    {
        $this->__load();
        return parent::setAclResgroup($aclResgroup);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'ip', 'dateCreated', 'dateExpired', 'message', 'priority', 'aclResgroup');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}