<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\Session
 *
 * @ORM\Table(name="SESSIONS")
 * @ORM\Entity
 */
class Session
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="SESk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $key
     *
     * @ORM\Column(name="SES_Key", type="string", length=32, nullable=false)
     */
    protected $key;

    /**
     * @var string $value
     *
     * @ORM\Column(name="SES_Value", type="text", nullable=true)
     */
    protected $value;

    /**
     * @var datetime $dateCreated
     *
     * @ORM\Column(name="SES_DateCreated", type="zenddate", nullable=false)
     */
    protected $dateCreated;

    /**
     * @var \Zend_Date $dateExpired
     *
     * @ORM\Column(name="SES_DateExpired", type="zenddate", nullable=false)
     */
    protected $dateExpired;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="SES_Ip", type="string", length=39, nullable=false)
     */
    protected $ip;

    /**
     * @var string $useragent
     *
     * @ORM\Column(name="SES_Useragent", type="string", length=150, nullable=false)
     */
    protected $useragent;

    /**
     * @var \PoradnikPiwny\Entities\User $user
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\User", inversedBy="sessions")
     * @ORM\JoinColumn(name="USR_1_Id", referencedColumnName="USRk_1_Id", onDelete="CASCADE")
     */
    protected $user;

    /*====================================================== */
    
    /**
     * @param string $key
     * @param string $value
     * @param \Zend_Date $dateExpired
     * @param string $ip
     * @param string $useragent
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function __construct($key, $value, $dateExpired, $ip, $useragent, $user) {
        $this->key = $key;
        $this->value = $value;
        $this->dateExpired = $dateExpired;
        $this->ip = $ip;
        $this->useragent = $useragent;
        $this->user = $user;
    }

    /*====================================================== */
    
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\Session
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * @param string $key
     * @return \PoradnikPiwny\Entities\Session
     */
    public function setKey($key) {
        $this->key = $key;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param string $value
     * @return \PoradnikPiwny\Entities\Session
     */
    public function setValue($value) {
        $this->value = $value;
        
        return $this;
    }

    /**
     * @return \Zend_Date
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * @param \Zend_Date $dateCreated
     * @return \PoradnikPiwny\Entities\Session
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
        
        return $this;
    }

    /**
     * @return \Zend_Date
     */
    public function getDateExpired() {
        return $this->dateExpired;
    }

    /**
     * @param \Zend_Date $dateExpired
     * @return \PoradnikPiwny\Entities\Session
     */
    public function setDateExpired($dateExpired) {
        $this->dateExpired = $dateExpired;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getIp() {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return \PoradnikPiwny\Entities\Session
     */
    public function setIp($ip) {
        $this->ip = $ip;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getUseragent() {
        return $this->useragent;
    }

    /**
     * @param string $useragent
     * @return \PoradnikPiwny\Entities\Session
     */
    public function setUseragent($useragent) {
        $this->useragent = $useragent;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny/Entities/User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param \PoradnikPiwny\Entities\User $user
     * @return \PoradnikPiwny\Entities\Session
     */
    public function setUser($user) {
        $this->user = $user;
        
        return $this;
    }
}