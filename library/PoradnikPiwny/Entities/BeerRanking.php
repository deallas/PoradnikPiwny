<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\BeerRanking
 *
 * @ORM\Table(name="beer_rankings")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerRankingsRepository")
 */
class BeerRanking
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BEERk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var float $value
     *
     * @ORM\Column(name="BEER_Value", type="decimal", precision=2, scale=1, nullable=false)
     */
    protected $value;

    /**
     * @var PoradnikPiwny\Entities\Beer $beer
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\Beer", inversedBy="beerRankings")
     * @ORM\JoinColumn(name="BEE_1_Id", referencedColumnName="BEEk_1_Id", onDelete="CASCADE")
     */
    protected $beer;

    /**
     * @var PoradnikPiwny\Entities\User $user
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\User", inversedBy="beerRankings")
     * @ORM\JoinColumn(name="USR_2_Id", referencedColumnName="USRk_1_Id", onDelete="CASCADE")
     */
    protected $user;
    
    /*====================================================== */
    
    /**
     * @param int $value
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function __construct($value,\PoradnikPiwny\Entities\Beer $beer,\PoradnikPiwny\Entities\User $user) {
        $this->value = $value;
        $this->beer = $beer;
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
     * @return \PoradnikPiwny\Entities\BeerRanking
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param int $value
     * @return \PoradnikPiwny\Entities\BeerRanking
     */
    public function setValue($value) {
        $this->value = $value;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function getBeer() {
        return $this->beer;
    }

     /**
     * @param  \PoradnikPiwny\Entities\Beer $beer
     * @return \PoradnikPiwny\Entities\BeerRanking $this
     */
    public function setBeer(\PoradnikPiwny\Entities\Beer $beer) {
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
     * @param  \PoradnikPiwny\Entities\User $user
     * @return \PoradnikPiwny\Entities\BeerRanking $this
     */
    public function setUser(\PoradnikPiwny\Entities\User $user) {
        $this->user = $user;
        
        return $this;
    }
}