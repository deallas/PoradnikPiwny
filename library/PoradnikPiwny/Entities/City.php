<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\City
 *
 * @ORM\Table(name="CITIES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\CitiesRepository")
 */
class City
{    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="CITk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="CIT_Name", type="string", length=100, nullable=false)
     */
    protected $name;
    
    /**
     * @var string $urlName
     * 
     * @ORM\Column(name="CIT_UrlName", type="string", length=100, nullable=false)
     */
    protected $urlName;
    
    /**
     * @var string $accentCityName
     * 
     * @ORM\Column(name="CIT_AccentCityName", type="string", length=100, nullable=false)
     */
    protected $accentCityName;
    
    /**
     * @var float $latitude
     * 
     * @ORM\Column(name="CIT_Latitude", type="decimal", precision=8, scale=5, nullable=true)
     */
    protected $latitude;

    /**
     * @var float $longitude
     * 
     * @ORM\Column(name="CIT_Longitude", type="decimal", precision=8, scale=5, nullable=true)
     */
    protected $longitude;
    
    /**
     * @var integer $population
     * 
     * @ORM\Column(name="CIT_Population", type="integer", length=10, nullable=true)
     */
    protected $population;
    
    /**
     * @var PoradnikPiwny\Entities\Region $region
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\Region")
     * @ORM\JoinColumn(name="REG_1_Id", referencedColumnName="REGk_1_Id", onDelete="CASCADE")
     */
    protected $region;

    /**
     * @var PoradnikPiwny\Entities\Country $country
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\Country")
     * @ORM\JoinColumn(name="COU_2_Id", referencedColumnName="COUk_1_Id", onDelete="CASCADE")
     */
    protected $country;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\Beer", mappedBy="city", cascade={"persist"})
     */
    protected $beers;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerManufacturer", mappedBy="city", cascade={"persist"})
     */
    protected $beerManufacturers;
    
    /**
     * @var ArrayCollection $beerSearches
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerSearch", mappedBy="city", cascade={"persist"})
     */
    protected $beerSearches;
    
    /*====================================================== */
    
    /**
     * @param string $name
     * @param \PoradnikPiwny\Entities\Region $region
     * @param \PoradnikPiwny\Entities\Country $country
     * @param string $accentCityName
     * @param float $latitude
     * @param float $longitude
     * @param int $population
     */
    public function __construct($name, $region, $country, $accentCityName, 
                                 $latitude = null, $longitude = null, $population = null) 
    {
        $this->setName($name);
        $this->region = $region;
        $this->country = $country;
        $this->accentCityName = $accentCityName;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->population = $population;
        
        $this->beers = new ArrayCollection();
        $this->beerManufacturers = new ArrayCollection();
        $this->beerSearches = new ArrayCollection();
    }
    
    /*====================================================== */
    
    /**
     * @return ArrayCollection
     */
    public function getBeers() {
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
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return \PoradnikPiwny\Entities\City
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
     * @return \PoradnikPiwny\Entities\City
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
     * @return \PoradnikPiwny\Entities\City
     */
    public function setUrlName($urlName) {
        $this->urlName = $urlName;

        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\Region
     */
    public function getRegion() {
        return $this->region;
    }

    /**
     * @param \PoradnikPiwny\Entities\Region $region
     * @return \PoradnikPiwny\Entities\City
     */
    public function setRegion($region) {
        $this->region = $region;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getAccentCityName() {
        return $this->accentCityName;
    }

    /**
     * @param string $accentCityName
     * @return \PoradnikPiwny\Entities\City
     */
    public function setAccentCityName($accentCityName) {
        $this->accentCityName = $accentCityName;
        
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return \PoradnikPiwny\Entities\City
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;
        
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return \PoradnikPiwny\Entities\City
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getPopulation() {
        return $this->population;
    }

    /**
     * @param int $population
     * @return \PoradnikPiwny\Entities\City
     */
    public function setPopulation($population) {
        $this->population = $population;
        
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
     * @return \PoradnikPiwny\Entities\City
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
            'region',
            'longitude',
            'latitude',
            'accentCityName',
            'population'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
}