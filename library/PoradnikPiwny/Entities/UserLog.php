<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\UserLog
 *
 * @ORM\Table(name="USER_LOGS")
 * @ORM\Entity
 */
class UserLog
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="USRLk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var int $type
     *
     * @ORM\Column(name="USRL_Type", type="smallint", nullable=false)
     */
    protected $type;

    /**
     * @var string $value
     *
     * @ORM\Column(name="USRL_Value", type="text", nullable=false)
     */
    protected $value;

    /**
     * @var \Zend_Date $dateAdded
     *
     * @ORM\Column(name="USRL_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded;

    /**
     * @var PoradnikPiwny\Entities\User $user
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\User", inversedBy="userLogs")
     * @ORM\JoinColumn(name="USR_1_Id", referencedColumnName="USRk_1_Id", onDelete="CASCADE")
     */
    protected $user;

    /*====================================================== */
    
    public function __construct($type, $value, $user) {
        $this->type = $type;
        $this->value = $value;
        $this->user = $user;
    }
    
    /*====================================================== */
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }
}