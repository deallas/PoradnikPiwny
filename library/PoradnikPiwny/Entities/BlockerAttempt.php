<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\BlockerAttempt
 *
 * @ORM\Table(name="BLOCKER_ATTEMPTS")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BlockerRepository")
 */
class BlockerAttempt
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BLOAk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @var string $ip
     * 
     * @ORM\Column(name="BLOA_Ip", type="string", nullable=false, length=39)
     */
    protected $ip;

    /**
     * @var \Zend_Date $dateExpired
     * 
     * @ORM\Column(name="BLOA_DateExpired", type="zenddate", nullable=false)
     */
    protected $dateExpired;
    
    /**
     * @var int $attempts
     * 
     * @ORM\Column(name="BLOA_Attempts", type="integer", nullable=false)
     */
    protected $attempts;
    
    /**
     *
     * @var \PoradnikPiwny\Entities\AclResgroup $aclResgroup
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\AclResgroup", inversedBy="blockerAttempts")
     * @ORM\JoinColumn(name="RESG_1_Id", referencedColumnName="RESGk_1_Id", onDelete="CASCADE")
     */
    protected $aclResgroup;
    
    /*====================================================== */
    
    /**
     * @param string $ip
     * @param \Zend_Date $dateExpired
     * @param int $attempts
     * @param \PoradnikPiwny\Entities\AclResgroup $aclResgroup
     */
    public function __construct($ip, $dateExpired, $attempts, $aclResgroup) {
        $this->ip = $ip;
        $this->dateExpired = $dateExpired;
        $this->attempts = $attempts;
        $this->aclResgroup = $aclResgroup;
    }

    
    /*====================================================== */
  
    public function incrementAttempts() {
        $this->attempts++;
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
     * @return \PoradnikPiwny\Entities\BlockerAttempt
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
     * @return \PoradnikPiwny\Entities\BlockerAttempt
     */
    public function setIp($ip) {
        $this->ip = $ip;
        
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
     * @return \PoradnikPiwny\Entities\BlockerAttempt
     */
    public function setDateExpired($dateExpired) {
        $this->dateExpired = $dateExpired;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getAttempts() {
        return $this->attempts;
    }

    /**
     * @param int $attempts
     * @return \PoradnikPiwny\Entities\BlockerAttempt
     */
    public function setAttempts($attempts) {
        $this->attempts = $attempts;
        
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
     * @return \PoradnikPiwny\Entities\BlockerAttempt
     */
    public function setAclResgroup($aclResgroup) {
        $this->aclResgroup = $aclResgroup;
        
        return $this;
    }
}
