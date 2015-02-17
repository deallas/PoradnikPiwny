<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\BeerPrice
 *
 * @ORM\Table(name="BEER_PRICES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerPricesRepository")
 */
class BeerPrice
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="BEEPk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var int $sizeOfBottle
     *
     * @ORM\Column(name="BEEP_SizeOfBottle", type="integer", nullable=false)
     */
    protected $sizeOfBottle;

    /**
     * @var float $price
     *
     * @ORM\Column(name="BEEP_Value", type="decimal", precision=5, scale=2, nullable=false)
     */
    protected $value;

    /**
     * @var \PoradnikPiwny\Entities\Beer
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Beer", inversedBy="beerPrices")
     * @ORM\JoinColumn(name="BEE_1_Id", referencedColumnName="BEEk_1_Id", onDelete="CASCADE")
     */
    protected $beer;

    /**
     * @var Currencies
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Currency", inversedBy="beerPrices")
     * @ORM\JoinColumn(name="CUR_2_Id", referencedColumnName="CURk_1_Id", onDelete="CASCADE")
     */
    protected $currency;

    /*====================================================== */
    
    /**
     * @param int $sizeOfBottle
     * @param float $value
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param \PoradnikPiwny\Entities\Currency $currency
     */
    public function __construct($sizeOfBottle, $value, $beer, $currency) {
        $this->sizeOfBottle = $sizeOfBottle;
        $this->value = $value;
        $this->beer = $beer;
        $this->currency = $currency;
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
     * @return \PoradnikPiwny\Entities\BeerPrice
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getSizeOfBottle() {
        return $this->sizeOfBottle;
    }

    /**
     * @param int $sizeOfBottle
     * @return \PoradnikPiwny\Entities\BeerPrice
     */
    public function setSizeOfBottle($sizeOfBottle) {
        $this->sizeOfBottle = $sizeOfBottle;
        
        return $this;
    }

    /**
     * @return float
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param float $value
     * @return \PoradnikPiwny\Entities\BeerPrice
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
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @return \PoradnikPiwny\Entities\BeerPrice
     */
    public function setBeer($beer) {
        $this->beer = $beer;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\Currency
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param \PoradnikPiwny\Entities\Currency $currency
     * @return \PoradnikPiwny\Entities\BeerPrice
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
        
        return $this;
    }  
}