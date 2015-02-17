<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctine\Common\Collections\ArrayCollection;

/**
 * PoradnikPiwny\Entities\Site
 *
 * @ORM\Table(name="SITES")
 * @ORM\Entity
 */
class Site
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="SITk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\SiteTranslation", mappedBy="site", cascade={"persist"})
     */
    protected $siteTranslations;
    
    /*====================================================== */
    
    /**
     * @param \PoradnikPiwny\Entities\SiteTranslation $st
     * @return \PoradnikPiwny\Entities\Site
     */
    public function addSiteTranslation($st)
    {
        $this->siteTranslations->add($st);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getSiteTranslations()
    {
        return $this->siteTranslations;
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
     * @return \PoradnikPiwny\Entities\Sites
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }
}