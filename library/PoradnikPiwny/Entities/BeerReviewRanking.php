<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\BeerReviewRanking
 *
 * @ORM\Table(name="BEER_REVIEW_RANKINGS")
 * @ORM\Entity
 */
class BeerReviewRanking
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BRRAk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \PoradnikPiwny\Entities\BeerReview $review
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerReview", inversedBy="beerReviewRankings")
     * @ORM\JoinColumn(name="BERE_1_Id", referencedColumnName="BEREk_1_Id", onDelete="CASCADE")
     */
    protected $review;

    /**
     * @var \PoradnikPiwny\Entities\User $user
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\User", inversedBy="beerReviewRankings")
     * @ORM\JoinColumn(name="USR_2_Id", referencedColumnName="USRk_1_Id", onDelete="CASCADE")
     */
    protected $user;

    /*====================================================== */
    
    /**
     * @param \PoradnikPiwny\Entities\BeerReview $review
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function __construct($review, $user) {
        $this->review = $review;
        $this->user = $user;
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
     * @return \PoradnikPiwny\Entities\BeerReviewRanking
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\BeerReview
     */
    public function getReview() {
        return $this->review;
    }

    /**
     * @param \PoradnikPiwny\Entities\BeerReview $review
     * @return \PoradnikPiwny\Entities\BeerReviewRanking
     */
    public function setReview($review) {
        $this->review = $review;
        
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
     * @return \PoradnikPiwny\Entities\BeerReviewRanking
     */
    public function setUser($user) {
        $this->user = $user;
        
        return $this;
    }
}