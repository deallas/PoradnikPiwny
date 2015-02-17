<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * PoradnikPiwny\Entities\Currency
 *
 * @ORM\Table(name="CURRENCIES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\CurrenciesRepository")
 */
class Currency
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="CURk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="CUR_Name", type="string", length=20, nullable=false)
     */
    protected $name;
    
    /**
     * @var string $shortcut
     * 
     * @ORM\Column(name="CUR_Shortcut", type="string", length=3, nullable=false)
     */
    protected $shortcut;
    
    /**
     * @var string $symbol
     * 
     * @ORM\Column(name="CUR_Symbol", type="string", length=3, nullable=false)
     */
    protected $symbol;
    
    /**
     * @var ArrayCollection $beerPrices
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerPrice", mappedBy="currency", cascade={"persist"})
     */    
    protected $beerPrices;

    /*====================================================== */
    
    /**
     * @param string $name
     * @param string $shortcut
     * @param string $symbol
     */
    public function __construct($name, $shortcut, $symbol) {
        $this->name = $name;  
        $this->shortcut = $shortcut;
        $this->symbol = $symbol;
        
        $this->beerPrices = new ArrayCollection();
    }
    
    /*====================================================== */
    
    /**
     * @param \PoradnikPiwny\Entities\BeerPrice $bp
     * @return \PoradnikPiwny\Entities\Currency
     */
    public function addBeerPrice(BeerPrice $bp)
    {
        $this->beerPrices->add($bp);
        
        return $this;
    }
    
    /**
     * @return \PoradnikPiwny\Entities\Currency
     */
    public function getBeerPrices()
    {
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
     * @param int $id
     * @return \PoradnikPiwny\Entities\Currency
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
     * @return \PoradnikPiwny\Entities\Currency
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getShortcut() {
        return $this->shortcut;
    }

    /**
     * @param string $shortcut
     * @return \PoradnikPiwny\Entities\Currency
     */
    public function setShortcut($shortcut) {
        $this->shortcut = $shortcut;
    
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSymbol() {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     * @return \PoradnikPiwny\Entities\Currency
     */
    public function setSymbol($symbol) {
        $this->symbol = $symbol;
        
        return $this;
    }
}