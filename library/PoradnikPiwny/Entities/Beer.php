<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\Beer
 *
 * @ORM\Table(name="BEERS")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeersRepository")
 */
class Beer
{
    const SLOD_JECZMIENNY = 'JECZMIENNY';
    const SLOD_PSZENNY = 'PSZENNY';
    const SLOD_INNY = 'INNY';
    
    const PIWO_BEZALKOHOLOWE = 'BEZALKOHOLOWE';
    const PIWO_LEKKIE = 'LEKKIE';
    const PIWO_PELNE = 'PELNE';
    const PIWO_MOCNE = 'MOCNE';
    
    const FILTOWANE_NIEWIEM = 'NIEWIEM';
    const FILTROWANE_NIE = 'NIE';
    const FILTROWANE_TAK = 'TAK';
    
    const PASTERYZOWANE_NIEWIEM = 'NIEWIEM';
    const PASTERYZOWANE_NIE = 'NIE';
    const PASTERYZOWANE_TAK = 'TAK';
    
    const SMAKOWE_NIEWIEM = 'NIEWIEM';
    const SMAKOWE_NIE = 'NIE';
    const SMAKOWE_TAK = 'TAK';
    
    const MIEJSCE_WARZENIA_BROWAR = 'BROWAR';
    const MIEJSCE_WARZENIA_RESTAURACJA = 'RESTAURACJA';
    const MIEJSCE_WARZENIA_DOM = 'DOM';
    
    const STATUS_AKTYWNY = 'AKTYWNY';
    const STATUS_NIEAKTYWNY = 'NIEAKTYWNY';
    const STATUS_DO_ZATWIERDZENIA = 'DO_ZATWIERDZENIA';
    const STATUS_ZAWIESZONY = 'ZAWIESZONY';
    const STATUS_USUNIETY = 'USUNIETY';
    
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BEEk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="BEE_Name", type="string", length=50, nullable=false)
     */
    protected $name;

    /**
     * @var string $urlName
     * 
     * @ORM\Column(name="BEE_UrlName", type="string", length=50, nullable=false)
     */
    protected $urlName;
    
    /**
     * @var float $alcohol
     *
     * @ORM\Column(name="BEE_Alcohol", type="decimal", precision=5, scale=2, nullable=true)
     */   
    protected $alcohol;
    
    /**
     * @var float $extract
     * 
     * @ORM\Column(name="BEE_Extract", type="decimal", precision=5, scale=2, nullable=true)
     */
    protected $extract;
    
    /**
     * @var string $malt
     * 
     * @ORM\Column(name="BEE_Malt", type="string", nullable=true) 
     */
    protected $malt; //słód - jęczmienne/pszenne/inne

    /**
     * @var string $type
     * 
     * @ORM\Column(name="BEE_Type", type="string", nullable=true) 
     */
    protected $type; // typ piwa - bezalkoholowe/lekkie/pełne/mocne
    
    /**
     * @var string $filtered
     * 
     * @ORM\Column(name="BEE_Filtered", type="string", nullable=false) 
     */
    protected $filtered; // filtrowane - niewiem/tak/nie

    /**
     * @var string $pasteurized
     * 
     * @ORM\Column(name="BEE_Pasteurized", type="string", nullable=false) 
     */
    protected $pasteurized; // pasteryzowane - niewiem/tak/nie

    /**
     * @var string $flavored
     * 
     * @ORM\Column(name="BEE_Flavored", type="string", nullable=false) 
     */    
    protected $flavored; // smakowe - niewiem/tak/nie
    
    /**
     * @var string $placeofbrew
     * 
     * @ORM\Column(name="BEE_Placeofbrew", type="string", nullable=true) 
     */
    protected $placeofbrew; // miejsce warzenia - browar/restauracja/dom

    /**
     * @var float $rankingTotal
     * 
     * @ORM\Column(name="BEE_RankingTotal", type="decimal", precision=12, scale=2, nullable=true) 
     */
    protected $rankingTotal = 0;
    
    /**
     * @var int $rankingCounter
     * 
     * @ORM\Column(name="BEE_RankingCounter", type="integer", nullable=true) 
     */
    protected $rankingCounter = 0;
 
    /**
     * @var float $rankingAvg
     *
     * @ORM\Column(name="BEE_RankingAvg", type="decimal", precision=4, scale=3, nullable=true)
     */    
    protected $rankingAvg;
    
    /**
     * @var float $rankingWeightedAvg
     * 
     * @ORM\Column(name="BEE_RankingWeightedAvg", type="decimal", precision=4, scale=3, nullable=true)
     */
    protected $rankingWeightedAvg;
    
    /**
     * @var \Zend_Date $dateAdded
     * 
     * @ORM\Column(name="BEE_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded = null;  
    
    /**
     * @var string $status
     * 
     * @ORM\Column(name="BEE_Status", type="string", nullable=true)
     */
    protected $status;
    
    /**
     * @var \PoradnikPiwny\Entities\BeerFamily $family
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerFamily", inversedBy="beers")
     * @ORM\JoinColumn(name="BEEF_1_Id", referencedColumnName="BEEFk_1_Id", onDelete="SET NULL")
     */
    protected $family; // rodzina piwa
    
    /**
     * @var \PoradnikPiwny\Entities\BeerDistributor $distributor
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerDistributor", inversedBy="beers")
     * @ORM\JoinColumn(name="BEED_2_Id", referencedColumnName="BEEDk_1_Id", onDelete="SET NULL")
     */
    protected $distributor;
    
    /**
     * @var \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerManufacturer", inversedBy="beers")
     * @ORM\JoinColumn(name="BEEM_3_Id", referencedColumnName="BEEMk_1_Id", onDelete="SET NULL")
     */
    protected $manufacturer;
    
    /**
     * @var \PoradnikPiwny\Entities\Country $country
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Country", inversedBy="beers")
     * @ORM\JoinColumn(name="COU_4_Id", referencedColumnName="COUk_1_Id", onDelete="SET NULL")
     */
    protected $country;
    
    /**
     * @var \PoradnikPiwny\Entities\Region $region
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Region", inversedBy="beers")
     * @ORM\JoinColumn(name="REG_5_Id", referencedColumnName="REGk_1_Id", onDelete="SET NULL")
     */
    protected $region;

    /**
     * @var \PoradnikPiwny\Entities\City $city
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\City", inversedBy="beers")
     * @ORM\JoinColumn(name="CIT_6_Id", referencedColumnName="CITk_1_Id", onDelete="SET NULL")
     */
    protected $city;
    
    /**
     * @var \PoradnikPiwny\Entities\BeerImage $image
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerImage", inversedBy="beers")
     * @ORM\JoinColumn(name="BEEI_7_Id", referencedColumnName="BEEIk_1_Id", onDelete="SET NULL")
     */
    protected $image;
    
    /**
     * @var ArrayCollection $beerRankings
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerRanking", mappedBy="beer", cascade={"persist"})
     */
    protected $beerRankings;

    /**
     * @var ArrayCollection $beerImages
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerImage", mappedBy="beer", cascade={"persist"})
     */
    protected $beerImages;
    
    /**
     * @var ArrayCollection $beerFavorites
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerFavorite", mappedBy="beer", cascade={"persist"})
     */    
    protected $beerFavorites;
    
    /**
     * @var ArrayCollection $beerComments
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerComment", mappedBy="beer", cascade={"persist"})
     */
    protected $beerComments;
    
    /**
     * @var ArrayCollection $beerTranslations
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerTranslation", mappedBy="beer", cascade={"persist"})
     */
    protected $beerTranslations;
    
    /**
     * @var ArrayCollection $beerPrices
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerPrice", mappedBy="beer", cascade={"persist"})
     */
    protected $beerPrices;

    /**
     * @var ArrayCollection $beerReviews
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerReview", mappedBy="beer", cascade={"persist"})
     */
    protected $beerReviews;
    
    /**
     * @var ArrayCollection $beerSearchConnections
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerSearchConnection", mappedBy="beer", cascade={"persist"})
     */
    protected $beerSearchConnections;
    
    /*====================================================== */
    
    /**
     * @param string $name
     * @param float $alcohol
     * @param float $extract
     * @param string $malt
     * @param int $type
     * @param string $filtered
     * @param string $pasteurized
     * @param string $flavored
     * @param string $placeofbrew
     * @param string $status
     * @param \PoradnikPiwny\Entities\BeerFamily $family
     * @param \PoradnikPiwny\Entites\BeerManufacturer $manufacturer
     * @param \PoradnikPiwny\Entites\Country $country
     * @param \PoradnikPiwny\Entites\Region $region
     * @param \PoradnikPiwny\Entites\City $city
     */
    public function __construct($name, $alcohol, $extract, 
                         $malt, $type, $filtered, $pasteurized, $flavored,
                         $placeofbrew, $status, $family = null, $distributor = null, 
                         $manufacturer = null, $country = null, 
                         $region = null, $city = null)
    {
        if($placeofbrew == 0) {
            $placeofbrew = null;
        }

        if($malt == 0) {
            $malt = null;
        }
        
        if($type == 0) {
            $type = null;
        }
        
        $this->setName($name);
        $this->alcohol = $alcohol;
        $this->extract = $extract;
        $this->malt = $malt;
        $this->type = $type;
        $this->setFiltered($filtered);
        $this->setPasteurized($pasteurized);
        $this->setFlavored($flavored);
        $this->placeofbrew = $placeofbrew;
        $this->status = $status;
        $this->family = $family;
        $this->distributor = $distributor;
        $this->manufacturer = $manufacturer;
        $this->country = $country;
        $this->region = $region;
        $this->city = $city;
        
        $this->beerRankings = new ArrayCollection();
        $this->beerImages = new ArrayCollection();
        $this->beerFavorites = new ArrayCollection();
        $this->beerComments = new ArrayCollection();
        $this->beerTranslation = new ArrayCollection();
        $this->beerPrices = new ArrayCollection();
        $this->beerReviews = new ArrayCollection();
        $this->beerSearchConnections = new ArrayCollection();
    }
    
    /*====================================================== */
        
    /**
     * @param \PoradnikPiwny\Entities\BeerRanking $ranking
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function addBeerRanking(BeerRanking $ranking)
    {
        $this->beerRankings->add($ranking);
        
        return $this;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerRankings()
    {
        return $this->beerRankings;
    }
       
    /**
     * @param \PoradnikPiwny\Entities\BeerImage $image
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function addBeerImage(BeerImage $image)
    {
        $this->beerImages->add($image);
        
        return $this;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerImages()
    {
        return $this->beerImages;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerFavorite $fav
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function addBeerFavorite(BeerFavorite $fav)
    {
        $this->beerFavorites->add($fav);
        
        return $this;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerFavorites()
    {
        return $this->beerFavorites;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerComment $comment
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function addBeerComment(BeerComment $comment)
    {
        $this->beerComments->add($comment);
        
        return $this;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerComments()
    {
        return $this->beerComments;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerTranslation $translation
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function addBeerTranslation(BeerTranslation $translation)
    {
        $this->beerTranslations->add($translation);
        
        return $this;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerTranslations()
    {
        return $this->beerTranslations;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerPrice $price
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function addBeerPrice(BeerPrice $price)
    {
        $this->beerPrices->add($price);
        
        return $this;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerPrices()
    {
        return $this->beerPrices;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerReview $review
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function addBeerReview(BeerReview $review)
    {
        $this->beerReviews->add($review);
        
        return $this;
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerReviews()
    {
        return $this->beerReviews;
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
     * @return \PoradnikPiwny\Entities\Beer
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
     * @return \PoradnikPiwny\Entities\Beer
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
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setUrlName($urlName) {
        $this->urlName = $urlName;

        return $this;
    }

    /**
     * @return float
     */
    public function getAlcohol() {
        return $this->alcohol;
    }

    /**
     * @param float $alcohol
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setAlcohol($alcohol) {
        if($alcohol != null) {
            if(empty($alcohol) || $alcohol == 0) {
                $alcohol = null;
            }
        }
        $this->alcohol = $alcohol;
        
        return $this;
    }

    /**
     * @return float
     */
    public function getExtract() {
        return $this->extract;
    }

    /**
     * @param float $extract
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setExtract($extract) {
        if($extract != null) {
            if(empty($extract) || $extract == 0) {
                $extract = null;
            }
        }
        $this->extract = $extract;
        
        return $this;
    }

    /**
     * @return integer
     */
    public function getMalt() {
        return $this->malt;
    }

    /**
     * @param string $malt
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setMalt($malt) {
        if(empty($malt)) $malt = null;
        $this->malt = $malt;
        
        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\BeerFamily
     */
    public function getFamily() {
        return $this->family;
    }

    /**
     * @param \PoradnikPiwny\Entities\BeerFamily $family
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setFamily($family) {
        $this->family = $family;
        
        return $this;
    }

    /**
     * @return integer
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param string $type
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setType($type) {
        if(empty($type)) $type = null;
        $this->type = $type;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getFiltered() {
        return $this->filtered;
    }

    /**
     * @param string $filtered
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setFiltered($filtered) {
        if($filtered == null) {
            $filtered = self::FILTOWANE_NIEWIEM;
        }
        $this->filtered = $filtered;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getPasteurized() {
        return $this->pasteurized;
    }

    /**
     * @param string $pasteurized
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setPasteurized($pasteurized) {
        if($pasteurized == null) {
            $pasteurized = self::PASTERYZOWANE_NIEWIEM;
        }
        $this->pasteurized = $pasteurized;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getFlavored() {
        return $this->flavored;
    }

    /**
     * @param string $flavored
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setFlavored($flavored) {
        if($flavored == null) {
            $flavored = self::SMAKOWE_NIEWIEM;
        }
        $this->flavored = $flavored;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getPlaceofbrew() {
        return $this->placeofbrew;
    }

    /**
     * @param string $placeofbrew
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setPlaceofbrew($placeofbrew) {
        if(empty($placeofbrew)) $placeofbrew = null;
        $this->placeofbrew = $placeofbrew;
        
        return $this;
    }

     /**
     * @return float
     */
    public function getRankingTotal() {
        return $this->rankingTotal;
    }
    
     /**
     * @param float $rankingTotal
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setRankingTotal($rankingTotal) {
        $this->rankingTotal = $rankingTotal;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getRankingCounter() {
        return $this->rankingCounter;
    }
    
     /**
     * @param int $rankingCounter
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setRankingCounter($rankingCounter) {
        $this->rankingCounter = $rankingCounter;
        
        return $this;
    }
    
    /**
     * @return float
     */
    public function getRankingAvg() {
        return $this->rankingAvg;
    }
    
     /**
     * @param float $rankingAvg
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setRankingAvg($rankingAvg) {
        $this->rankingAvg = $rankingAvg;
        
        return $this;
    }
    
    /**
     * @return float
     */
    public function getRankingWeightedAvg() {
        return $this->rankingWeightedAvg;
    }

    /**
     * @param float $rankingWeightedAvg
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setRankingWeightedAvg($rankingWeightedAvg) {
        $this->rankingWeightedAvg = $rankingWeightedAvg;
        
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
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setDistributor($distributor) {
        $this->distributor = $distributor;
    
        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function getManufacturer() {
        return $this->manufacturer;
    }

    /**
     * @param \PoradnikPiwny\Entities\BeerManufacturer $manufacturer
     * @return \PoradnikPiwny\Entities\Beer $this
     */
    public function setManufacturer($manufacturer) {
        $this->manufacturer = $manufacturer;
        
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
     * @return \PoradnikPiwny\Entities\Beer $this
     */
    public function setCountry($country) {
        $this->country = $country;    

        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\Region
     */
    public function getRegion()
    {
        return $this->region;
    }
    
    /**
     * @param  \PoradnikPiwny\Entities\Region $region
     * @return \PoradnikPiwny\Entities\Beer $this
     */
    public function setRegion($region)
    {
        $this->region = $region;
    
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\City
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * @param  \PoradnikPiwny\Entities\City $city
     * @return \PoradnikPiwny\Entities\Beer $this
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * @param \PoradnikPiwny\Entities\BeerImage $image
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setImage($image) {
        $this->image = $image;
    
        return $this;
    }
    
    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setStatus($status) {
        $this->status = $status;
        
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
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
        
        return $this;
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
            'urlName',
            'alcohol',
            'extract',
            'malt',
            'type',
            'filtered',
            'pasteurized',
            'flavored',
            'placeofbrew',
            'rankingAvg',
            'rankingWeightedAvg',
            'dateAdded',
            'family',
            'manufacturer',
            'distributor',
            'image',
            'country',
            'region',
            'city'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
    
    /*====================================================== */
    
    /**
     * @return array
     */
    public static function getEnumStatus()
    {
        return array(
            self::STATUS_AKTYWNY,
            self::STATUS_NIEAKTYWNY,
            self::STATUS_DO_ZATWIERDZENIA,
            self::STATUS_ZAWIESZONY
        );
    }
}