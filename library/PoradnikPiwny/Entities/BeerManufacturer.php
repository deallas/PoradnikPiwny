<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\BeerManufacturer
 *
 * @ORM\Table(name="beer_manufacturers")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerManufacturersRepository")
 */
class BeerManufacturer 
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BEEMk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="BEEM_Name", type="string", length=50, nullable=false)
     */
    protected $name;
    
    /**
     * @var string $urlName
     * 
     * @ORM\Column(name="BEEM_UrlName", type="string", length=50, nullable=false)
     */
    protected $urlName;
    
    /**
     * @var string $email
     * 
     * @ORM\Column(name="BEEM_Email", type="string", length=50, nullable=true)
     */
    protected $email;
    
    /**
     * @var string $website
     * 
     * @ORM\Column(name="BEEM_Website", type="string", length=100, nullable=true)
     */
    protected $website;
    
    /**
     * @var string $address
     * 
     * @ORM\Column(name="BEEM_Address", type="string", length=100, nullable=true)
     */
    protected $address;
    
    /**
     * @var float $latitude
     * 
     * @ORM\Column(name="BEEM_Latitude", type="decimal", precision=8, scale=5, nullable=true)
     */
    protected $latitude;
    
    /**
     * @var float $longitude
     * 
     * @ORM\Column(name="BEEM_Longitude", type="decimal", precision=8, scale=5, nullable=true)
     */
    protected $longitude;
    
    /**
     * @var PoradnikPiwny\Entities\BeerDistributor $distributor
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerDistributor", inversedBy="beerManufacturers")
     * @ORM\JoinColumn(name="BEED_1_Id", referencedColumnName="BEEDk_1_Id", onDelete="CASCADE")
     */
    protected $distributor;
  
    /**
     * @var PoradnikPiwny\Entities\Country $country
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Country", inversedBy="beerManufacturers")
     * @ORM\JoinColumn(name="COU_4_Id", referencedColumnName="COUk_1_Id", onDelete="SET NULL")
     */
    protected $country;
    
    /**
     * @var PoradnikPiwny\Entities\Region $region
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Region", inversedBy="beerManufacturers")
     * @ORM\JoinColumn(name="REG_3_Id", referencedColumnName="REGk_1_Id", onDelete="SET NULL")
     */
    protected $region;
    
    /**
     * @var PoradnikPiwny\Entities\City $city
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\City", inversedBy="beerManufacturers")
     * @ORM\JoinColumn(name="CIT_2_Id", referencedColumnName="CITk_1_Id", onDelete="SET NULL")
     */
    protected $city;
    
    /**
     * @var \PoradnikPiwny\Entities\BeerManufacturerImage $beerManufacturerImage
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerManufacturerImage", inversedBy="beerManufacturers")
     * @ORM\JoinColumn(name="BEMI_5_Id", referencedColumnName="BEMIk_1_Id", onDelete="SET NULL")
     */
    protected $beerManufacturerImage;
   
    /**
     * @var ArrayCollection $beerManufacturerImages
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerManufacturerImage", mappedBy="beerManufacturer", cascade={"persist"})
     */
    protected $beerManufacturerImages;
    
    /**
     * @var ArrayCollection $beers
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\Beer", mappedBy="manufacturer", cascade={"persist"})
     */
    protected $beers;
    
    /**
     * @var ArrayCollection $beerManufacturerTranslations
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerManufacturerTranslation", mappedBy="beerManufacturer", cascade={"persist"})
     */    
    protected $beerManufacturerTranslations;
    
    /**
     * @var ArrayCollection $beerSearches
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerSearch", mappedBy="beerManufacturer", cascade={"persist"})
     */
    protected $beerSearches;
    
    /*====================================================== */
    
    /**
     * @param string $name
     * @param string $website
     * @param string $email
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @param \PoradnikPiwny\Entities\Country $country
     * @param \PoradnikPiwny\Entities\Region $region
     * @param \PoradnikPiwny\Entities\City $city
     * @param string $address
     * @param float $longitude
     * @param float $latitude
     */
    public function __construct($name, $website, $email, $distributor, 
                                 $country = null, $region = null, $city = null,
                                 $address = null, $longitude = null, $latitude = null) {
        $this->setName($name);
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->distributor = $distributor;
        $this->country = $country;
        $this->region = $region;
        $this->city = $city;
        $this->email = $email;
        $this->website = $website;
        $this->address = $address;
        
        $this->beers = new ArrayCollection();
        $this->beerManufacturerTranslations = new ArrayCollection();
        $this->beerSearches = new ArrayCollection();
        $this->beerManufacturerImages = new ArrayCollection();
    }
    
    /*====================================================== */
    
    /**
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function addBeer(Beer $beer) {
        $this->beers->add($beer);
        
        return $this;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeers() {
        return $this->beers;
    }  
    
    /**
     * @param \PoradnikPiwny\Entities\BeerManufacturerTranslation $trans
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function addManufacturerTranslation(BeerManufacturerTranslation $trans)
    {
        $this->beerManufacturerTranslations->add($trans);
        
        return $this;
    }
    
    public function getManufacturerTranslations()
    {
        return $this->beerManufacturerTranslations;
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
     * @return \PoradnikPiwny\Entities\BeerManufacturer
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
     * @return \PoradnikPiwny\Entities\BeerManufacturer
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
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setUrlName($urlName) {
        $this->urlName = $urlName;
    
        return $this;
    }
    
    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setEmail($email) {
        $this->email = $email;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getWebsite() {
        return $this->website;
    }

    /**
     * @param string $website
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setWebsite($website) {
        $this->website = $website;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @param string $address
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setAddress($address) {
        $this->address = $address;
        
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
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setLatitude($latitude) {
        $this->latitude = (float)$latitude;
        
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
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setLongitude($longitude) {
        $this->longitude = (float)$longitude;
        
        return $this;
    }
     
    /**
     * @return \PoradnikPiwny\Entities\BeerDistributor
     */
    public function getDistributor() {
        return $this->distributor;
    }

    /**
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setDistributor($distributor) {
        $this->distributor = $distributor;
        
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
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setCountry($country) {
        $this->country = $country;
        
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
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setRegion($region) {
        $this->region = $region;
        
        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\City
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param \PoradnikPiwny\Entities\City $city
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setCity($city) {
        $this->city = $city;
        
        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     */
    public function getBeerManufacturerImage() {
        return $this->beerManufacturerImage;
    }

    /**
     * @param \PoradnikPiwny\Entities\BeerManufacturerImage $beerManufacturerImage
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function setBeerManufacturerImage($beerManufacturerImage) {
        $this->beerManufacturerImage = $beerManufacturerImage;
    
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
            'email',
            'website',
            'city',
            'region',
            'country',
            'beerManufacturerImage'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
}