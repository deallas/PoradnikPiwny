<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\BeerFavorite
 *
 * @ORM\Table(name="beer_favorites")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerFavoriteRepository")
 */
class BeerFavorite
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BEFVk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var PoradnikPiwny\Entities\Beer $beer
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\Beer", inversedBy="beerFavorites")
     * @ORM\JoinColumn(name="beer_id", referencedColumnName="BEEk_1_Id", onDelete="CASCADE")
     */
    protected $beer;

    /**
     * @var PoradnikPiwny\Entities\User $user
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\User", inversedBy="beerFavorites")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="USRk_1_Id", onDelete="CASCADE")
     */
    protected $user;
    
    /*====================================================== */
    
    /**
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function __construct(\PoradnikPiwny\Entities\Beer $beer,
                                \PoradnikPiwny\Entities\User $user) {
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
     * @return \PoradnikPiwny\Entities\BeerFavorite
     */
    public function setId($id) {
        $this->id = $id;
        
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
     * @return \PoradnikPiwny\Entities\BeerFavorite
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
     * @return \PoradnikPiwny\Entities\BeerFavorite
     */
    public function setUser(\PoradnikPiwny\Entities\User $user) {
        $this->user = $user;
        
        return $this;
    }
}