<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\BlockerRule
 *
 * @ORM\Table(name="BLOCKER_RULES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BlockerRepository")
 */
class BlockerRule
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BLORk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @var string $ip
     * 
     * @ORM\Column(name="BLOR_Ip", type="string", nullable=false, length=39)
     */
    protected $ip;
    
    /**
     * @var \Zend_Date $dateCreated
     * 
     * @ORM\Column(name="BLOR_DateCreated", type="zenddate", nullable=false)
     */
    protected $dateCreated;
    
    /**
     * @var \Zend_Date $dateExpired
     * 
     * @ORM\Column(name="BLOR_DateExpired", type="zenddate", nullable=true)
     */
    protected $dateExpired;
    
    /**
     * @var string $message
     * 
     * @ORM\Column(name="BLOR_Message", type="text") 
     */
    protected $message;
    
    /**
     * @var int $priority
     * 
     * @ORM\Column(name="BLOR_Priority", type="integer", nullable=false)
     */
    protected $priority;
    
    /**
     *
     * @var \PoradnikPiwny\Entities\AclResgroup $aclResgroup
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\AclResgroup", inversedBy="blockerRules")
     * @ORM\JoinColumn(name="RESG_1_Id", referencedColumnName="RESGk_1_Id", onDelete="CASCADE")
     */
    protected $aclResgroup;
    
    /*====================================================== */
    
    /**
     * @param string $ip
     * @param \Zend_Date $dateExpired
     * @param int $priority
     * @param string $message
     * @param \PoradnikPiwny\Entities\AclResgroup $aclResgroup
     */
    public function __construct($ip, $dateExpired, $priority, $message, $aclResgroup) {
        $this->ip = $ip;
        $this->dateExpired = $dateExpired;
        $this->priority = $priority;
        $this->message = $message;
        $this->aclResgroup = $aclResgroup;
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
     * @return \PoradnikPiwny\Entities\BlockerRule
     */
    public function setId($id) {
        $this->id = $id;
        
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
     * @return \PoradnikPiwny\Entities\BlockerRule
     */
    public function setIp($ip) {
        $this->ip = $ip;
        
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
     * @return \PoradnikPiwny\Entities\BlockerRule
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
     * @return \PoradnikPiwny\Entities\BlockerRule
     */
    public function setDateExpired($dateExpired) {
        $this->dateExpired = $dateExpired;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param string $message
     * @return \PoradnikPiwny\Entities\BlockerRule
     */
    public function setMessage($message) {
        $this->message = $message;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return \PoradnikPiwny\Entities\BlockerRule
     */
    public function setPriority($priority) {
        $this->priority = $priority;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\AclResgroup
     */
    public function getAclResgroup() {
        return $this->aclResgroup;
    }

    /**
     * @param \PoradnikPiwny\Entities\AclResgroup $aclResgroup
     * @return \PoradnikPiwny\Entities\BlockerRule
     */
    public function setAclResgroup($aclResgroup) {
        $this->aclResgroup = $aclResgroup;
        
        return $this;
    }
}
