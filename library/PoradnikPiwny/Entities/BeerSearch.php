<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * PoradnikPiwny\Entities\BeerSearch
 *
 * @ORM\Table(name="BEER_SEARCHES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerSearchesRepository")
 */
class BeerSearch
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BEESk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $uid
     *
     * @ORM\Column(name="BEES_Uid", type="string", length=32, nullable=false)
     */
    protected $uid;
    
    /**
     * @var string $query
     *
     * @ORM\Column(name="BEES_Query", type="string", length=50, nullable=false)
     */
    protected $query;
    
    /**
     * @var float $rankingMin
     *
     * @ORM\Column(name="BEES_RankingAvg_Min", type="decimal", precision=4, scale=3, nullable=true)
     */   
    protected $rankingMin;
    
    /**
     * @var float $rankingMax
     *
     * @ORM\Column(name="BEES_RankingAvg_Max", type="decimal", precision=4, scale=3, nullable=true)
     */   
    protected $rankingMax;

    /**
     * @var float $alcoholMin
     * 
     * @ORM\Column(name="BEES_Alcohol_Min", type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $alcoholMin;
 
    /**
     * @var float $alcoholMax
     * 
     * @ORM\Column(name="BEES_Alcohol_Max", type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $alcoholMax;
    
    /**
     * @var float $extractMin
     * 
     * @ORM\Column(name="BEES_Extract_Min", type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $extractMin;
 
    /**
     * @var float $extractMax
     * 
     * @ORM\Column(name="BEES_Extract_Max", type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $extractMax;
    
    /**
     * @var int $malt
     * 
     * @ORM\Column(name="BEES_Malt", type="smallint", length=1, nullable=true) 
     */
    protected $malt; //słód - jęczmienne/pszenne/inne

    /**
     * @var int $malt
     * 
     * @ORM\Column(name="BEES_Type", type="smallint", length=1, nullable=true) 
     */
    protected $type; // typ piwa - bezalkoholowe/lekkie/pełne/mocne
    
    /**
     * @var string $filtered
     * 
     * @ORM\Column(name="BEES_Filtered", type="string", nullable=false) 
     */
    protected $filtered; // filtrowane - niewiem/tak/nie

    /**
     * @var string $pasteurized
     * 
     * @ORM\Column(name="BEES_Pasteurized", type="string", nullable=false) 
     */
    protected $pasteurized; // pasteryzowane - niewiem/tak/nie

    /**
     * @var string $flavored
     * 
     * @ORM\Column(name="BEES_Flavored", type="string", nullable=false) 
     */    
    protected $flavored; // smakowe - niewiem/tak/nie
    
    /**
     * @var string $placeofbrew
     * 
     * @ORM\Column(name="BEES_Placeofbrew", type="string") 
     */
    protected $placeofbrew; // miejsce warzenia - browar/restauracja/dom
    
    /**
     * @var \Zend_Date $dateAdded
     * 
     * @ORM\Column(name="BEES_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded = null;
    
    /**
     * @var \PoradnikPiwny\Entities\User $user
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\User", inversedBy="beerSearches")
     * @ORM\JoinColumn(name="USR_1_Id", referencedColumnName="USRk_1_Id", onDelete="SET NULL")
     */
    protected $user;
    
    /**
     * @var \PoradnikPiwny\Entities\BeerFamily $family
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerFamily", inversedBy="beerSearches")
     * @ORM\JoinColumn(name="BEEF_2_Id", referencedColumnName="BEEFk_1_Id", onDelete="SET NULL")
     */
    protected $family; // rodzina piwa
  
    /**
     * @var \PoradnikPiwny\Entities\BeerDistributor $distributor
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerDistributor", inversedBy="beerSearches")
     * @ORM\JoinColumn(name="BEED_3_Id", referencedColumnName="BEEDk_1_Id", onDelete="SET NULL")
     */
    protected $distributor;
    
    /**
     * @var \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerManufacturer", inversedBy="beerSearches")
     * @ORM\JoinColumn(name="BEEM_4_Id", referencedColumnName="BEEMk_1_Id", onDelete="SET NULL")
     */
    protected $manufacturer;
    
    /**
     * @var \PoradnikPiwny\Entities\Country $country
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Country", inversedBy="beerSearches")
     * @ORM\JoinColumn(name="COU_5_Id", referencedColumnName="COUk_1_Id", onDelete="SET NULL")
     */
    protected $country;
    
    /**
     * @var \PoradnikPiwny\Entities\Region $region
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Region", inversedBy="beerSearches")
     * @ORM\JoinColumn(name="REG_6_Id", referencedColumnName="REGk_1_Id", onDelete="SET NULL")
     */
    protected $region;

    /**
     * @var \PoradnikPiwny\Entities\City $city
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\City", inversedBy="beerSearches")
     * @ORM\JoinColumn(name="CIT_7_Id", referencedColumnName="CITk_1_Id", onDelete="SET NULL")
     */
    protected $city;
    
    /**
     * @var ArrayCollection $beerSearchConnections
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerSearchConnection", mappedBy="beerSearch", cascade={"persist"})
     */
    protected $beerSearchConnections;
    
    /*====================================================== */
    
    /**
     * @param string $uid
     * @param string $query
     * @param \PoradnikPiwny\Entities\User $user
     * @param float $ranking_min
     * @param float $ranking_max
     * @param float $alcohol_min
     * @param float $alcohol_max
     * @param float $extract_min
     * @param float $extract_max
     * @param string $malt
     * @param string $type
     * @param string $filtered
     * @param string $pasteurized
     * @param string $flavored
     * @param string $placeOfBrew
     * @param \PoradnikPiwny\Entities\BeerFamily $family
     * @param \PoradnikPiwny\Entities\BeerDistributor $distributor
     * @param \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     * @param \PoradnikPiwny\Entities\Country $country
     * @param \PoradnikPiwny\Entities\Region $region
     * @param \PoradnikPiwny\Entities\City $city
     */
    public function __construct($uid, $query, $user, $ranking_min, $ranking_max,
                                 $alcohol_min, $alcohol_max, $extract_min, $extract_max,
                                 $malt, $type, $filtered, $pasteurized,
                                 $flavored, $placeOfBrew, $family, $distributor,
                                 $manufacturer, $country, $region, $city) {
        
        $this->uid = $uid;
        $this->query = $query;
        $this->user = $user;
        $this->placeOfBrew = $placeOfBrew;
        $this->family = $family;
        $this->distributor = $distributor;
        $this->manufacturer = $manufacturer;
        $this->country = $country;
        $this->region = $region;
        $this->city = $city;        
        
        $this->setRankingMin($ranking_min)
             ->setRankingMax($ranking_max)
             ->setAlcoholMin($alcohol_min)
             ->setAlcoholMax($alcohol_max)
             ->setExtractMin($extract_min)
             ->setExtractMax($extract_max)
             ->setMalt($malt)
             ->setType($type)
             ->setPlaceofbrew($placeOfBrew)
             ->setPasteurized($pasteurized)
             ->setFiltered($filtered)
             ->setFlavored($flavored);
        
        $this->beerSearchConnections = new ArrayCollection();
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
     * @return \PoradnikPiwny\Entities\BeerSearch
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getUid() {
        return $this->uid;
    }

    /**
     * @param string $uid
     * @return \PoradnikPiwny\Entities\BeerSearch
     */
    public function setUid($uid) {
        $this->uid = $uid;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @param string $query
     * @return \PoradnikPiwny\Entities\BeerSearch
     */
    public function setQuery($query) {
        $this->query = $query;
        
        return $this;
    }

    /**
     * @return \Zend_Date
     */
    public function getDateAdded() {
        return $this->dateAdded;
    }

    /**
     * @param \Zend_Date $dateAdded
     * @return \PoradnikPiwny\Entities\BeerSearch
     */
    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
        
        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param \PoradnikPiwny\Entities\User $user
     * @return \PoradnikPiwny\Entities\BeerSearch
     */
    public function setUser($user) {
        $this->user = $user;
        
        return $this;
    }
    
    public function getRankingMin() {
        return $this->rankingMin;
    }

    public function setRankingMin($rankingMin) {
        $this->rankingMin = floatval($rankingMin);
        
        return $this;
    }

    public function getRankingMax() {
        return $this->rankingMax;
    }

    public function setRankingMax($rankingMax) {
        $this->rankingMax = floatval($rankingMax);
        
        return $this;
    }

    public function getAlcoholMin() {
        return $this->alcoholMin;
    }

    public function setAlcoholMin($alcoholMin) {
        $this->alcoholMin = floatval($alcoholMin);
        
        return $this;
    }

    public function getAlcoholMax() {
        return $this->alcoholMax;
    }

    public function setAlcoholMax($alcoholMax) {
        $this->alcoholMax = floatval($alcoholMax);
        
        return $this;
    }

    public function getExtractMin() {
        return $this->extractMin;
    }

    public function setExtractMin($extractMin) {
        $this->extractMin = floatval($extractMin);
        
        return $this;
    }

    public function getExtractMax() {
        return $this->extractMax;
    }

    public function setExtractMax($extractMax) {
        $this->extractMax = floatval($extractMax);
        
        return $this;
    }

    public function getMalt() {
        return $this->malt;
    }

    public function setMalt($malt) {
        if($malt == 0) $malt = null;
        $this->malt = $malt;
        
        return $this;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        if($type == 0) $type = null;
        $this->type = $type;
        
        return $this;
    }

    public function getFiltered() {
        return $this->filtered;
    }

    public function setFiltered($filtered) {
        if($filtered == null) {
            $filtered = Beer::FILTOWANE_NIEWIEM;
        }
        $this->filtered = $filtered;
        
        return $this;
    }

    public function getPasteurized() {
        return $this->pasteurized;
    }

    public function setPasteurized($pasteurized) {
        if($pasteurized == null) {
            $pasteurized = Beer::PASTERYZOWANE_NIEWIEM;
        }
        $this->pasteurized = $pasteurized;
        
        return $this;
    }

    public function getFlavored() {
        return $this->flavored;
    }

    public function setFlavored($flavored) {
        if($flavored == null) {
            $flavored = Beer::SMAKOWE_NIEWIEM;
        }
        $this->flavored = $flavored;
        
        return $this;
    }

    public function getPlaceofbrew() {
        return $this->placeofbrew;
    }

    public function setPlaceofbrew($placeofbrew) {
        if($placeofbrew == 0) $placeofbrew = null;
        $this->placeofbrew = $placeofbrew;
        
        return $this;
    }

    public function getFamily() {
        return $this->family;
    }

    public function setFamily($family) {
        $this->family = $family;
        
        return $this;
    }

    public function getDistributor() {
        return $this->distributor;
    }

    public function setDistributor($distributor) {
        $this->distributor = $distributor;
        
        return $this;
    }

    public function getManufacturer() {
        return $this->manufacturer;
    }

    public function setManufacturer($manufacturer) {
        $this->manufacturer = $manufacturer;
        
        return $this;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country) {
        $this->country = $country;
        
        return $this;
    }

    public function getRegion() {
        return $this->region;
    }

    public function setRegion($region) {
        $this->region = $region;
        
        return $this;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
        
        return $this;
    }
}