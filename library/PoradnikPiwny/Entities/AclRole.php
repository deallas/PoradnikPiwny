<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * PoradnikPiwny\Entities\AclRole
 *
 * @ORM\Table(name="ACL_ROLES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\AclRepository")
 */
class AclRole 
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="ROLk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="ROL_Name", type="string", length=30, nullable=false)
     */
    protected $name;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\User", mappedBy="role", cascade={"persist"})
     */
    protected $users;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\AclRule", mappedBy="role", cascade={"persist"})
     */    
    protected $aclRules;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\AclRoleParent", mappedBy="roleParent", cascade={"persist"})
     */ 
    protected $aclRoleChilds;
    
    /*====================================================== */
    
    /**
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
        
        $this->users = new ArrayCollection();
        $this->aclRules = new ArrayCollection();
        $this->aclRoleChilds = new ArrayCollection();
    }
    
    /*====================================================== */
    
    /**
     * @param \PoradnikPiwny\Entities\User $user
     * @return \PoradnikPiwny\Entities\AclRole
     */
    public function addUser($user)
    {
        $this->users->add($user);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\AclRule $rule
     * @return \PoradnikPiwny\Entities\AclRole
     */
    public function addAclRule($rule)
    {
        $this->aclRules->add($rule);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getAclRules()
    {
        return $this->aclRules;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\AclRoleParent $child
     * @return \PoradnikPiwny\Entities\AclRole
     */
    public function addAclRoleChild(AclRoleParent $child)
    {
        $this->aclRoleChilds->add($child);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getAclRoleChilds()
    {
        return $this->aclRoleChilds;
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
     * @return \PoradnikPiwny\Entities\AclRole
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     * @return \PoradnikPiwny\Entities\AclRole
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;
    }
}
