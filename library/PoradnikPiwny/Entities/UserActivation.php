<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\UserActivation
 *
 * @ORM\Table(name="USER_ACTIVATIONS")
 * @ORM\Entity
 */
class UserActivation
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="USRAk_1_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \Zend_Date $dateAdded
     *
     * @ORM\Column(name="USRA_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded;

    /**
     * @var \Zend_Date $dateExpired
     *
     * @ORM\Column(name="USRA_DateExpired", type="zenddate", nullable=false)
     */
    protected $dateExpired;

    /**
     * @var string $uuid
     *
     * @ORM\Column(name="USRA_UUID", type="string", length=36, nullable=false)
     */
    protected $uuid;

    /**
     * @var int $type
     *
     * @ORM\Column(name="USRA_Type", type="smallint", nullable=false)
     */
    protected $type;

    /**
     * @var PoradnikPiwny\Entities\User $user
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\User", inversedBy="userActivations")
     * @ORM\JoinColumn(name="USR_1_Id", referencedColumnName="USRk_1_Id", onDelete="CASCADE")
     */
    protected $user;

    /*====================================================== */
    
    public function __construct($dateExpired, $uuid, $type, $user) {
        $this->dateExpired = $dateExpired;
        $this->uuid = $uuid;
        $this->type = $type;
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
     * @return \PoradnikPiwny\Entities\UserActivation
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return \Zend_Date
     */
    public function getDateAdded() {
        return $this->dateAdded;
    }

    /**
     * @param \Zend_Date $dateAdded
     * @return \PoradnikPiwny\Entities\UserActivation
     */
    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
        
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
     * @return \PoradnikPiwny\Entities\UserActivation
     */
    public function setDateExpired($dateExpired) {
        $this->dateExpired = $dateExpired;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getUuid() {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     * @return \PoradnikPiwny\Entities\UserActivation
     */
    public function setUuid($uuid) {
        $this->uuid = $uuid;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param int $type
     * @return \PoradnikPiwny\Entities\UserActivation
     */
    public function setType($type) {
        $this->type = $type;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param \PoradnikPiwny\Entities\User $user
     * @return \PoradnikPiwny\Entities\UserActivation
     */
    public function setUser($user) {
        $this->user = $user;
        
        return $this;
    }
}