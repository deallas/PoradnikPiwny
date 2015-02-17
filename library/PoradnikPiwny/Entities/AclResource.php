<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * PoradnikPiwny\Entities\AclResource
 *
 * @ORM\Table(name="ACL_RESOURCES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\AclRepository")
 */
class AclResource
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="RESk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="RES_Name", type="string", length=30, nullable=false)
     */
    protected $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\AclRule", mappedBy="resource", cascade={"persist"})
     */    
    protected $aclRules;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\AclPrivilege", mappedBy="resource", cascade={"persist"})
     */
    protected $aclPrivileges;
    
    /*====================================================== */
    
    /**
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
        
        $this->aclRules = new ArrayCollection();
        $this->aclPrivileges = new ArrayCollection();
    }
    
    /*====================================================== */
    
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

    /*====================================================== */
    
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\AclResource
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
     * @return \PoradnikPiwny\Entities\AclResource
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;
    }    
}