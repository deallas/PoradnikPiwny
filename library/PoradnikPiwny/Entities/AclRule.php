<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\AclRule
 *
 * @ORM\Table(name="ACL_RULES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\AclRepository")
 */
class AclRule
{
    const ACTION_DENIED = 'DENIED';
    const ACTION_ALLOWED = 'ALLOWED';
    
    /**
     * @var int $id
     *
     * @ORM\Column(name="RULk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var int $action
     *
     * @ORM\Column(name="RUL_Action", type="smallint", nullable=false)
     */
    protected $action;

    /**
     * @var int $priority
     *
     * @ORM\Column(name="RUL_Priority", type="integer", nullable=false)
     */
    protected $priority;

    /**
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\AclRole", inversedBy="aclRules")
     * @ORM\JoinColumn(name="ROL_1_Id", referencedColumnName="ROLk_1_Id", onDelete="CASCADE")
     */
    protected $role;

    /**
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\AclResource", inversedBy="aclRules")
     * @ORM\JoinColumn(name="RES_2_Id", referencedColumnName="RESk_1_Id", onDelete="CASCADE")
     */
    protected $resource;

    /*====================================================== */
    
    /**
     * 
     * @param int $action
     * @param int $priority
     * @param \PoradnikPiwny\Entities\AclRole $role
     * @param \PoradnikPiwny\Entities\AclResource $resource
     */
    public function __construct($action, $priority, $role, $resource) {
        $this->action = $action;
        $this->priority = $priority;
        $this->role = $role;
        $this->resource = $resource;
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
     * @return \PoradnikPiwny\Entities\AclRule
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * @param int $action
     * @return \PoradnikPiwny\Entities\AclRule
     */
    public function setAction($action) {
        $this->action = $action; 
        
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
     * @return \PoradnikPiwny\Entities\AclRule
     */
    public function setPriority($priority) {
        $this->priority = $priority;
                
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\AclRole
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @param \PoradnikPiwny\Entities\AclRole $role
     * @return \PoradnikPiwny\Entities\AclRule
     */
    public function setRole($role) {
        $this->role = $role;
                
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\AclResource
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     * @param \PoradnikPiwny\Entities\AclResource $resource
     * @return \PoradnikPiwny\Entities\AclRule
     */
    public function setResource($resource) {
        $this->resource = $resource;
                
        return $this;
    }
}