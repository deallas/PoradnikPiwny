<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\UserMeta
 *
 * @ORM\Table(name="USER_METAS")
 * @ORM\Entity
 */
class UserMeta
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="USRMk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $key
     *
     * @ORM\Column(name="USRM_Key", type="string", length=30, nullable=true)
     */
    protected $key;

    /**
     * @var string $value
     *
     * @ORM\Column(name="USRM_Value", type="text", nullable=true)
     */
    protected $value;

    /**
     * @var PoradnikPiwny\Entities\User $user
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\User", inversedBy="userMetas")
     * @ORM\JoinColumn(name="USR_1_Id", referencedColumnName="USRk_1_Id", onDelete="CASCADE")
     */
    protected $user;

    /*====================================================== */
    
    /**
     * @param string $key
     * @param string $value
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function __construct($key = null, $value = null, $user = null) {
        $this->key = $key;
        $this->value = $value;
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
     * @return \PoradnikPiwny\Entities\Usermeta
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
     * @return \PoradnikPiwny\Entities\Usermeta
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
     * @return \PoradnikPiwny\Entities\Usermeta
     */
    public function setValue($value) {
        $this->value = $value;
        
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
     * @return \PoradnikPiwny\Entities\Usermeta
     */
    public function setUser($user) {
        $this->user = $user;
        
        return $this;
    }
}