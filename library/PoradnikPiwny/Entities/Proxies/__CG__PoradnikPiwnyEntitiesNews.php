<?php

namespace PoradnikPiwny\Entities\Proxies\__CG__\PoradnikPiwny\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class News extends \PoradnikPiwny\Entities\News implements \Doctrine\ORM\Proxy\Proxy
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

    
    public function addNewsComment($nc)
    {
        $this->__load();
        return parent::addNewsComment($nc);
    }

    public function getNewsComments()
    {
        $this->__load();
        return parent::getNewsComments();
    }

    public function addNewsTranslation($nt)
    {
        $this->__load();
        return parent::addNewsTranslation($nt);
    }

    public function getNewsTranslations()
    {
        $this->__load();
        return parent::getNewsTranslations();
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

    public function getDateAdded()
    {
        $this->__load();
        return parent::getDateAdded();
    }

    public function setDateAdded($dateAdded)
    {
        $this->__load();
        return parent::setDateAdded($dateAdded);
    }

    public function getStatus()
    {
        $this->__load();
        return parent::getStatus();
    }

    public function setStatus($status)
    {
        $this->__load();
        return parent::setStatus($status);
    }

    public function getUser()
    {
        $this->__load();
        return parent::getUser();
    }

    public function setUser($user)
    {
        $this->__load();
        return parent::setUser($user);
    }

    public function getComments()
    {
        $this->__load();
        return parent::getComments();
    }

    public function addComment($comment)
    {
        $this->__load();
        return parent::addComment($comment);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'dateAdded', 'status', 'user', 'newsComments', 'newsTranslations');
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