<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\AclRoleParent
 *
 * @ORM\Table(name="ACL_ROLE_PARENTS")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\AclRepository")
 */
class AclRoleParent
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="ROLPk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\AclRole")
     * @ORM\JoinColumn(name="ROL_1_Id", referencedColumnName="ROLk_1_Id", onDelete="CASCADE")
     */
    protected $role;

    /**
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\AclRole", inversedBy="aclRoleChilds")
     * @ORM\JoinColumn(name="ROL_2_Id_Parent", referencedColumnName="ROLk_1_Id", onDelete="CASCADE")
     */
    protected $roleParent;

    /*====================================================== */

    /**
     * @param \PoradnikPiwny\Entities\AclRole $role
     * @param \PoradnikPiwny\Entities\AclRole $roleParent
     */
    public function __construct($role, $roleParent) {
        $this->role = $role;
        $this->roleParent = $roleParent;
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
     * @return \PoradnikPiwny\Entities\AclRoleParent
     */
    public function setId($id) {
        $this->id = $id;
        
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
     * @return \PoradnikPiwny\Entities\AclRoleParent
     */
    public function setRole($role) {
        $this->role = $role;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\AclRole
     */
    public function getRoleParent() {
        return $this->roleParent;
    }

    /**
     * @param \PoradnikPiwny\Entities\AclRole $roleParent
     * @return \PoradnikPiwny\Entities\AclRoleParent
     */
    public function setRoleParent($roleParent) {
        $this->roleParent = $roleParent;
        
        return $this;
    }
}