<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\BeerFamily
 *
 * @ORM\Table(name="beer_families")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerFamiliesRepository")
 */
class BeerFamily
{    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BEEFk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="BEEF_Name", type="string", length=50, nullable=false)
     */
    protected $name;

    /**
     * @var string $urlName
     * 
     * @ORM\Column(name="BEEF_UrlName", type="string", length=50, nullable=false)
     */
    protected $urlName;
    
    /**
     * @var ArrayCollection
     * @ORM\OrderBy({"name" = "ASC"})
     * @ORM\ManyToMany(targetEntity="\PoradnikPiwny\Entities\BeerFamily")
     * @ORM\JoinTable(name="beer_family_parents",
     *      joinColumns={@ORM\JoinColumn(name="BEEF_1_Id", referencedColumnName="BEEFk_1_Id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="BEEF_2_Id_Parent", referencedColumnName="BEEFk_1_Id")}
     *      )
     */
    protected $parents;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\Beer", mappedBy="family", cascade={"persist"})
     */
    protected $beers;
    
    /**
     * @var ArrayCollection $beerSearches
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerSearch", mappedBy="family", cascade={"persist"})
     */
    protected $beerSearches;

    /*====================================================== */
    
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);
        $this->beers = new ArrayCollection();
        $this->beerSearches = new ArrayCollection();
        $this->parents = new ArrayCollection();
    }

    /*====================================================== */
    
    /**
     * @param \PoradnikPiwny\Entities\BeerFamily $parent
     */
    public function addParent($parent)
    {
       $this->parents->add($parent);
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerFamily $parent
     */
    public function removeParent($parent)
    {
        $this->parents->removeElement($parent);
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeers()
    {
        return $this->beers;
    }    
    
    /*====================================================== */
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\BeerFamily $this
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     * @return \PoradnikPiwny\Entities\BeerFamily $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->urlName = urlencode(strtolower(Tool::utf8ToAscii($this->name, '')));
    
        return $this;
    }
    
    /**
     * @return string
     */
    public function getUrlName() {
        return $this->urlName;
    }

    /**
     * @param string $urlName
     * @return \PoradnikPiwny\Entities\BeerFamily
     */
    public function setUrlName($urlName) {
        $this->urlName = $urlName;

        return $this;
    }
    
    public function __toString() 
    {
        return $this->name;
    }
    
    /*====================================================== */
    
    /**
     * @param array $g_params
     * @return array
     */
    public function toArray($g_params = array()) {
        
        $a_params = array(
            'id',
            'name',
            'urlName'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
}