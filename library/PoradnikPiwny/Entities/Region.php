<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\Region
 *
 * @ORM\Table(name="REGIONS")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\RegionsRepository")
 */
class Region
{    
    /**
     * @var int $id
     *
     * @ORM\Column(name="REGk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="REG_Name", type="string", length=100, nullable=false)
     */
    protected $name;
    
    /**
     * @var string $urlName
     * 
     * @ORM\Column(name="REG_UrlName", type="string", length=100, nullable=false)
     */
    protected $urlName;
    
    /**
     * @var string $fipsCode
     *
     * @ORM\Column(name="REG_FIPSCode", type="string", length=2, nullable=false)
     */
    protected $fipsCode;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\Beer", mappedBy="region", cascade={"persist"})
     */
    protected $beers;
    
    /**
     * @var PoradnikPiwny\Entities\Country $country
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\Country")
     * @ORM\JoinColumn(name="COU_1_Id", referencedColumnName="COUk_1_Id", onDelete="CASCADE")
     */
    protected $country;
    
    /**
     * @var ArrayCollection $beerSearches
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerSearch", mappedBy="region", cascade={"persist"})
     */
    protected $beerSearches;
    
    /**
     * @var ArrayCollection $beerManufacturers
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerManufacturer", mappedBy="region", cascade={"persist"})
     */
    protected $beerManufacturers;    
    
    /*====================================================== */
    
    /**
     * @param string $name
     * @param string $fipsCode
     * @param \PoradnikPiwny\Entities\Country $country
     */
    public function __construct($name, $fipsCode, $country) {
        $this->setName($name);
        $this->urlName = urlencode(Tool::utf8ToAscii($this->name, ''));
        $this->fipsCode = $fipsCode;
        $this->country = $country;

        $this->beers = new ArrayCollection();
        $this->beerSearches = new ArrayCollection();
        $this->beerManufacturers = new ArrayCollection();
    }
    
    /*====================================================== */
    
    /**
     * @return ArrayCollection
     */
    public function getBeers()
    {
        return $this->beers;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @return \PoradnikPiwny\Entities\Region
     */
    public function addBeer($beer)
    {
        $this->beers->add($beer);
        
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
     * @return \PoradnikPiwny\Entities\Region
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
     * @return \PoradnikPiwny\Entities\Region
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
     * @return \PoradnikPiwny\Entities\Region
     */
    public function setUrlName($urlName) {
        $this->urlName = $urlName;

        return $this;
    }
    
    /**
     * @return string
     */
    public function getFipsCode() {
        return $this->fipsCode;
    }

    /**
     * @param string $fipsCode
     * @return \PoradnikPiwny\Entities\Region
     */
    public function setFipsCode($fipsCode) {
        $this->fipsCode = $fipsCode;
        
        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\Country
     */
    public function getCountry() {
        return $this->country;
    }

    /**
     * @param \PoradnikPiwny\Entities\Country $country
     * @return \PoradnikPiwny\Entities\Region
     */
    public function setCountry($country) {
        $this->country = $country;
        
        return $this;
    }
    
    /**
     * @param array $g_params
     * @return array
     */
    public function toArray($g_params = array()) {
        
        $a_params = array(
            'id',
            'name',
            'urlName',
            'country',
            'fipsCode'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
}