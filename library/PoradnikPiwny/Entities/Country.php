<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\Country
 *
 * @ORM\Table(name="COUNTRIES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\CountriesRepository")
 */
class Country
{    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="COUk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="COU_Name", type="string", length=50, nullable=false)
     */
    protected $name;

    /**
     * @var string $urlName
     * 
     * @ORM\Column(name="COU_UrlName", type="string", length=50, nullable=false)
     */
    protected $urlName;
    
    /**
     * @var string $iso2Code
     * 
     * @ORM\Column(name="COU_Iso2Code", type="string", length=2, nullable=false)
     */
    protected $iso2Code;

    /**
     * @var string $iso3Code
     * 
     * @ORM\Column(name="COU_Iso3Code", type="string", length=3, nullable=false)
     */
    protected $iso3Code;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\Beer", mappedBy="country", cascade={"persist"})
     */
    protected $beers;
    
    /**
     * @var ArrayCollection $beerSearches
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerSearch", mappedBy="country", cascade={"persist"})
     */
    protected $beerSearches;
    
    /**
     * @var ArrayCollection $beerManufacturers
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerManufacturer", mappedBy="country", cascade={"persist"})
     */
    protected $beerManufacturers;    

    /*====================================================== */
    
    /**
     * @param string $name
     * @param string $iso2Code
     * @param string $iso3Code
     */
    public function __construct($name, $iso2Code, $iso3Code)
    {
        $this->setName($name);
        $this->urlName = urlencode(Tool::utf8ToAscii($this->name, ''));
        $this->iso2Code = $iso2Code;
        $this->iso3Code = $iso3Code;
        
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
     * @param  int $id
     * @return \PoradnikPiwny\Entities\Country
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
     * @param  string $name 
     * @return \PoradnikPiwny\Entities\Country
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
     * @return \PoradnikPiwny\Entities\Country
     */
    public function setUrlName($urlName) {
        $this->urlName = $urlName;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getIso2Code() {
        return $this->iso2Code;
    }

    /**
     * @param string $iso2Code
     * @return \PoradnikPiwny\Entities\Country
     */
    public function setIso2Code($iso2Code) {
        $this->iso2Code = $iso2Code;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getIso3Code() {
        return $this->iso3Code;
    }

    /**
     * @param string $iso3Code
     * @return \PoradnikPiwny\Entities\Country
     */
    public function setIso3Code($iso3Code) {
        $this->iso3Code = $iso3Code;
        
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
            'iso2code',
            'iso3code'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
}