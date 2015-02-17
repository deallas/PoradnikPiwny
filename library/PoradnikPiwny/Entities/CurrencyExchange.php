<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\CurrencyExchange
 *
 * @ORM\Table(name="CURRENCY_EXCHANGES")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\CurrenciesRepository")
 */
class CurrencyExchange
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="CUREk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var float $multiplier
     * 
     * @ORM\Column(name="CURE_Multiplier", type="decimal", precision=8, scale=4, nullable=true)
     */
    protected $multiplier;
    
    /**
     *
     * @var \Zend_Db $lastUpdated
     * 
     * @ORM\Column(name="CURE_LastUpdated", type="zenddate", nullable=false)
     */
    protected $lastUpdated;
    
    /**
     * @var PoradnikPiwny\Entities\CurrencyExchange $currency
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\Currency")
     * @ORM\JoinColumn(name="CUR_1_Id", referencedColumnName="CURk_1_Id", onDelete="CASCADE")
     */
    protected $currency;

    /**
     * @var PoradnikPiwny\Entities\CurrencyExchange $currencyExchanged
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\Currency")
     * @ORM\JoinColumn(name="CUR_2_Id", referencedColumnName="CURk_1_Id", onDelete="CASCADE")
     */
    protected $currencyExchanged;
    
    /*====================================================== */
    
    /**
     * 
     * @param float $multiplier
     * @param \PoradnikPiwny\Entities\Currency $currency
     * @param \PoradnikPiwny\Entities\Currency $currencyExchanged
     */
    public function __construct($multiplier, $currency, $currencyExchanged) {
        $this->multiplier = $multiplier;
        $this->currency = $currency;
        $this->currencyExchanged = $currencyExchanged;
    }
    
    /*====================================================== */

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    public function getMultiplier() {
        return $this->multiplier;
    }

    public function setMultiplier($multiplier) {
        $this->multiplier = $multiplier;
        
        return $this;
    }
    
    public function getLastUpdated() {
        return $this->lastUpdated;
    }

    public function setLastUpdated($lastUpdated) {
        $this->lastUpdated = $lastUpdated;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function setCurrency($currency) {
        $this->currency = $currency;
        
        return $this;
    }

    public function getCurrencyExchanged() {
        return $this->currencyExchanged;
    }

    public function setCurrencyExchanged($currencyExchanged) {
        $this->currencyExchanged = $currencyExchanged;
        
        return $this;
    }
}