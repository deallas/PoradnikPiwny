<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\SiteTranslation
 *
 * @ORM\Table(name="SITE_TRANSLATIONS")
 * @ORM\Entity
 */
class SiteTranslation
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="SITTk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="SITT_Name", type="string", length=50, nullable=false)
     */
    protected $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="SITT_Description", type="string", length=200, nullable=false)
     */
    protected $description;

    /**
     * @var text $text
     *
     * @ORM\Column(name="SITT_Text", type="text", nullable=false)
     */
    protected $text;

    /**
     * @var int $sittLang
     *
     * @ORM\Column(name="SITT_Lang", type="smallint", nullable=false)
     */
    protected $lang;

    /**
     * @var \PoradnikPiwny\Entities\Site $site
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\Site", inversedBy="siteTranslations")
     * @ORM\JoinColumn(name="SIT_1_Id", referencedColumnName="SITk_1_Id", onDelete="CASCADE")
     */
    protected $site;

    /*====================================================== */
    
    public function __construct($name, $description, $text, $lang, $site) {
        $this->name = $name;
        $this->description = $description;
        $this->text = $text;
        $this->lang = $lang;
        $this->site = $site;
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
     * @return \PoradnikPiwny\Entities\SiteTranslation
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
     * @return \PoradnikPiwny\Entities\SiteTranslation
     */
    public function setName($name) {
        $this->name = $name;
        
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
     * @return \PoradnikPiwny\Entities\SiteTranslation
     */
    public function setDescription($description) {
        $this->description = $description;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param string $text
     * @return \PoradnikPiwny\Entities\SiteTranslation
     */
    public function setText($text) {
        $this->text = $text;
        
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
     * @return \PoradnikPiwny\Entities\SiteTranslation
     */
    public function setLang($lang) {
        $this->lang = $lang;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\Site
     */
    public function getSite() {
        return $this->site;
    }

    /**
     * @param \PoradnikPiwny\Entities\Site $site
     * @return \PoradnikPiwny\Entities\SiteTranslation
     */
    public function setSite($site) {
        $this->site = $site;
        
        return $this;
    }
}