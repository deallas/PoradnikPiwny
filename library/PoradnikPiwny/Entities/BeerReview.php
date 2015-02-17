<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * PoradnikPiwny\Entities\BeerReview
 *
 * @ORM\Table(name="BEER_REVIEWS")
 * @ORM\Entity
 */
class BeerReview
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="BEREk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $description
     *
     * @ORM\Column(name="BERE_Description", type="text", nullable=false)
     */
    protected $description;

    /**
     * @var string $foam
     *
     * @ORM\Column(name="BERE_Foam", type="string", length=200, nullable=true)
     */
    protected $foam;

    /**
     * @var string $flavor
     *
     * @ORM\Column(name="BERE_Flavor", type="string", length=200, nullable=true)
     */
    protected $flavor;

    /**
     * @var string $color
     *
     * @ORM\Column(name="BERE_Color", type="string", length=30, nullable=true)
     */
    protected $color;

    /**
     * @var int $status
     *
     * @ORM\Column(name="BERE_Status", type="smallint", nullable=false)
     */
    protected $status;

    /**
     * @var int $lang
     *
     * @ORM\Column(name="BERE_Lang", type="smallint", nullable=false)
     */
    protected $lang;

    /**
     * @var int $rankingTotal
     *
     * @ORM\Column(name="BERE_RankingTotal", type="integer", nullable=true)
     */
    protected $rankingTotal;

    /**
     * @var \PoradnikPiwny\Entities\Beer
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Beer", inversedBy="beerReviews")
     * @ORM\JoinColumn(name="BEE_1_Id", referencedColumnName="BEEk_1_Id", onDelete="CASCADE")
     */
    protected $beer;

    /**
     * @var \PoradnikPiwny\Entities\User
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\User", inversedBy="beerReviews")
     * @ORM\JoinColumn(name="USR_2_Id", referencedColumnName="USRk_1_Id", onDelete="SET NULL")
     */
    protected $user;
    
    /**
     * @var ArrayCollection $beerReviewRankings
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerReviewRanking", mappedBy="review", cascade={"persist"})
     */
    protected $beerReviewRankings;

    /*====================================================== */
    
    /**
     * 
     * @param string $description
     * @param string $foam
     * @param string $flavor
     * @param string $color
     * @param int $status
     * @param int $lang
     * @param int $rankingTotal
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function __construct($description, $foam, $flavor, $color, 
                                $status, $lang, $rankingTotal, 
                                $beer, $user) {
        $this->description = $description;
        $this->foam = $foam;
        $this->flavor = $flavor;
        $this->color = $color;
        $this->status = $status;
        $this->lang = $lang;
        $this->rankingTotal = $rankingTotal;
        $this->beer = $beer;
        $this->user = $user;
        
        $this->beerReviewRankings = new ArrayCollection();
    }
    
    /*====================================================== */
    
    /**
     * @param \PoradnikPiwny\Entities\BeerReviewRanking $ranking
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function addRanking($ranking)
    {
        $this->beerReviewRankings->add($ranking);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getRankings()
    {
        return $this->beerReviewRankings;
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
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setDescription($description) {
        $this->description = $description;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getFoam() {
        return $this->foam;
    }

    /**
     * @param string $foam
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setFoam($foam) {
        $this->foam = $foam;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getFlavor() {
        return $this->flavor;
    }

    /**
     * @param string $flavor
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setFlavor($flavor) {
        $this->flavor = $flavor;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

    /**
     * @param string $color
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setColor($color) {
        $this->color = $color;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param int $status
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setStatus($status) {
        $this->status = $status;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getLang() {
        return $this->lang;
    }

    /**
     * @param int $lang
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setLang($lang) {
        $this->lang = $lang;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getRankingTotal() {
        return $this->rankingTotal;
    }

    /**
     * @param int $rankingTotal
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setRankingTotal($rankingTotal) {
        $this->rankingTotal = $rankingTotal;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function getBeer() {
        return $this->beer;
    }

    /**
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setBeer($beer) {
        $this->beer = $beer;
        
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
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function setUser($user) {
        $this->user = $user;
        
        return $this;
    }
}