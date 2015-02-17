<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\BeerTranslation
 *
 * @ORM\Table(name="BEER_TRANSLATIONS")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeersRepository")
 */
class BeerTranslation
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="BEETk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var text $description
     *
     * @ORM\Column(name="BEET_Description", type="text", nullable=false)
     */
    protected $description;

    /**
     * @var string $lang
     *
     * @ORM\Column(name="BEET_Lang", type="string", nullable=false)
     */
    protected $lang;

    /**
     * @var \PoradnikPiwny\Entities\Beer $beer
     *
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Beer", inversedBy="beerTranslations")
     * @ORM\JoinColumn(name="BEE_1_Id", referencedColumnName="BEEk_1_Id", onDelete="CASCADE")
     */
    protected $beer;

    /*====================================================== */
    
    /**
     * 
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param string $lang
     * @param string $description
     */
    public function __construct($beer, $lang, $description) {
        $this->description = $description;
        $this->lang = $lang;
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
     * @return \PoradnikPiwny\Entities\Beer
     */
    public function getBeer() {
        return $this->beer;
    }

    /**
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @return \PoradnikPiwny\Entities\BeerTranslation
     */
    public function setBeer($beer) {
        $this->beer = $beer;
        
        return $this;
    }
    
    /**
     * @param array $g_params
     * @return array
     */
    public function toArray($g_params = array()) {
        
        $a_params = array(
            'id',
            'description',
            'lang',
            'beer'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
}