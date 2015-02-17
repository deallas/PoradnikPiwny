<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    WS\Tool,
    PoradnikPiwny\Entities\Beer,
    PoradnikPiwny\Entities\BeerManufacturer;

/**
 * PoradnikPiwny\Entities\BeerDistributor
 *
 * @ORM\Table(name="beer_distributors")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerDistributorsRepository")
 */
class BeerDistributor
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BEEDk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="BEED_Name", type="string", length=50, nullable=false)
     */
    protected $name;
    
    /**
     * @var string $urlName
     * 
     * @ORM\Column(name="BEED_UrlName", type="string", length=50, nullable=false)
     */
    protected $urlName;

    /**
     * @var ArrayCollection $beers
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\Beer", mappedBy="distributor", cascade={"persist"})
     */
    protected $beers;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerManufacturer", mappedBy="distributor", cascade={"persist"})
     */
    protected $beerManufacturers;
    
    /**
     * @var ArrayCollection $beerSearches
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerSearch", mappedBy="distributor", cascade={"persist"})
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
        $this->beerManufacturers = new ArrayCollection();
        $this->beerSearches = new ArrayCollection();
    }
    
    /*====================================================== */
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeers() {
        return $this->beers;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @return \PoradnikPiwny\Entities\BeerDistributor
     */
    public function addBeer(Beer $beer)
    {
        $this->beers->add($beer);
        
        return $this;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerManufacturers() {
        return $this->beerManufacturers;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerManufacturer $bm
     * @return \PoradnikPiwny\Entities\BeerDistributor
     */
    public function addBeerManufacturer(BeerManufacturer $bm) 
    {
        $this->beerManufacturers->add($bm);
        
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
     * @return \PoradnikPiwny\Entities\BeerDistributor
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
     * @return \PoradnikPiwny\Entities\BeerDistributor
     */
    public function setName($name) {
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
     * @return \PoradnikPiwny\Entities\BeerDistributor
     */
    public function setUrlName($urlName) {
        $this->urlName = $urlName;
    
        return $this;
    }
    
    /**
     * @param array $g_params
     * @return array
     */
    public function toArray($g_params = array()) {
        
        $a_params = array(
            'id',
            'name'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
}
