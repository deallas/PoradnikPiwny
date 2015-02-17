<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * PoradnikPiwny\Entities\AclResgroup
 *
 * @ORM\Table(name="ACL_RESGROUPS")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\AclRepository")
 */
class AclResgroup 
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="RESGk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="RESG_Name", type="string", length=30, nullable=false)
     */
    protected $name;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BlockerAttempt", mappedBy="aclResgroup", cascade={"persist"})
     */
    protected $blockerAttempts;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BlockerRule", mappedBy="aclResgroup", cascade={"persist"})
     */    
    protected $blockerRules;
    
    /*====================================================== */
    
    /**
     * @param string $name
     */
    public function __construct($name) 
    {
        $this->name = $name;
        
        $this->blockerAttempts = new ArrayCollection();
        $this->blockerRules = new ArrayCollection();
    }
    
    /*====================================================== */
    
    /**
     * @return ArrayCollection
     */
    public function getBlockerAttempts()
    {
        return $this->blockerAttempts;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BlockerAttempt $attempt
     * @return \PoradnikPiwny\Entities\AclResgroup
     */
    public function addBlockerAttempt(BlockerAttempt $attempt)
    {
        $this->blockerAttempts->add($attempt);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getBlockerRules()
    {
        return $this->blockerRules;
    }
    
    /** 
     * @param \PoradnikPiwny\Entities\BlockerRule $rule
     * @return \PoradnikPiwny\Entities\AclResgroup
     */
    public function addBlockerRule(BlockerRule $rule)
    {
        $this->blockerRules->add($rule);
        
        return $this;
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
     * @return \PoradnikPiwny\Entities\AclResgroup
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
     * @return \PoradnikPiwny\Entities\AclResgroup
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;
    }
}