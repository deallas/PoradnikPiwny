<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\AclPrivilege
 *
 * @ORM\Table(name="ACL_PRIVILEGES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\AclRepository")
 */
class AclPrivilege
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="PRIVk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="PRIV_Name", type="string", length=30, nullable=false)
     */
    protected $name;

    /**
     * @var \PoradnikPiwny\Entities\AclResource $resource
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\AclResource", inversedBy="aclPrivileges")
     * @ORM\JoinColumn(name="RES_1_Id", referencedColumnName="RESk_1_Id", onDelete="CASCADE")
     */
    protected $resource;

    /*====================================================== */
    
    /**
     * @param string $name
     * @param \PoradnikPiwny\Entities\AclResource $resource
     */
    public function __construct($name, $resource) {
        $this->name = $name;
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
     * @return \PoradnikPiwny\Entities\AclPrivileges
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
     * @return \PoradnikPiwny\Entities\AclPrivileges
     */
    public function setName($name) {
        $this->name = $name;
        
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
     * @return \PoradnikPiwny\Entities\AclPrivileges
     */
    public function setResource($resource) {
        $this->resource = $resource;
        
        return $this;
    }
}