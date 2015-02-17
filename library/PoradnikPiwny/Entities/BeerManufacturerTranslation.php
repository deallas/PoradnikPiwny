<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\BeerManufacturerTranslation
 *
 * @ORM\Table(name="BEER_MANUFACTURER_TRANSLATIONS")
 * @ORM\Entity
 */
class BeerManufacturerTranslation
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="BEMTk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var text $description
     *
     * @ORM\Column(name="BEMT_Description", type="text", nullable=false)
     */
    protected $description;

    /**
     * @var string $lang
     *
     * @ORM\Column(name="BEMT_Lang", type="string", nullable=false)
     */
    protected $lang;

    /**
     * @var \PoradnikPiwny\Entities\BeerManufacturer $beerManufacturer
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\BeerManufacturer", inversedBy="beerManufacturerTranslations")
     * @ORM\JoinColumn(name="BEEM_1_Id", referencedColumnName="BEEMk_1_Id", onDelete="CASCADE")
     */
    protected $beerManufacturer;

    /*====================================================== */
    
    /**
     * @param \PoradnikPiwny\Entities\BeerManufacturer $beerManufacturer
     * @param string $lang
     * @param string $description
     */
    public function __construct($beerManufacturer, $lang, $description) {
        $this->description = $description;
        $this->lang = $lang;
        $this->beerManufacturer = $beerManufacturer;
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
     * @return \PoradnikPiwny\Entities\BeerTranslation
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
     * @return \PoradnikPiwny\Entities\BeerTranslation
     */
    public function setDescription($description) {
        $this->description = $description;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getLang() {
        return $this->lang;
    }

    /**
     * @param string $lang
     * @return \PoradnikPiwny\Entities\BeerTranslation
     */
    public function setLang($lang) {
        $this->lang = $lang;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\BeerManufacturer
     */
    public function getBeerManufacturer() {
        return $this->beerManufacturer;
    }

    /**
     * @param \PoradnikPiwny\Entities\BeerManufacturer $beerManufacturer
     * @return \PoradnikPiwny\Entities\BeerManufacturerTranslation
     */
    public function setBeerManufacturer($beerManufacturer) {
        $this->beerManufacturer = $beerManufacturer;
    
        return $this;
    }
}