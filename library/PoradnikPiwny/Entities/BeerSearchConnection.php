<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\BeerSearchConnection
 *
 * @ORM\Table(name="BEER_SEARCH_CONNECTIONS")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerSearchesRepository")
 */
class BeerSearchConnection
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BESCk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @var \PoradnikPiwny\Entities\BeerSearch $beerSearch
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerSearch", inversedBy="beerSearchConnections")
     * @ORM\JoinColumn(name="BEES_1_Id", referencedColumnName="BEESk_1_Id", onDelete="CASCADE")
     */
    protected $beerSearch;   
    
    
    /**
     * @var \PoradnikPiwny\Entities\Beer $beer
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Beer", inversedBy="beerSearchConnections")
     * @ORM\JoinColumn(name="BEE_2_Id", referencedColumnName="BEEk_1_Id", onDelete="CASCADE")
     */
    protected $beer;
    
    /*====================================================== */

    public function __construct($beerSearch, $beer) {
        $this->beerSearch = $beerSearch;
        $this->beer = $beer;
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
     * @return \PoradnikPiwny\Entities\BeerSearchConnection
     */
    public function setId($id) {
        $this->id = $id;
    
        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\BeerSearch
     */
    public function getBeerSearch() {
        return $this->beerSearch;
    }

    /**
     * @param \PoradnikPiwny\Entities\BeerSearch $beerSearch
     * @return \PoradnikPiwny\Entities\BeerSearchConnection
     */
    public function setBeerSearch($beerSearch) {
        $this->beerSearch = $beerSearch;
        
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
     * @return \PoradnikPiwny\Entities\BeerSearchConnection
     */
    public function setBeer($beer) {
        $this->beer = $beer;
        
        return $this;
    }
}