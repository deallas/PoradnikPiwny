<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\NewsTranslation
 *
 * @ORM\Table(name="NEWS_TRANSLATION")
 * @ORM\Entity
 */
class NewsTranslation
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="NEWTk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="NEWT_Name", type="string", length=50, nullable=false)
     */
    protected $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="NEWT_Description", type="string", length=200, nullable=false)
     */
    protected $description;

    /**
     * @var text $text
     *
     * @ORM\Column(name="NEWT_Text", type="text", nullable=false)
     */
    protected $text;

    /**
     * @var int $lang
     *
     * @ORM\Column(name="NEWT_Lang", type="smallint", nullable=false)
     */
    protected $lang;

    /**
     * @var \PoradnikPiwny\Entities\News $news
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\News", inversedBy="newsTranslations")
     * @ORM\JoinColumn(name="NEWS_1_Id", referencedColumnName="NEWSk_1_Id", onDelete="CASCADE")
     */
    protected $news;

    /*====================================================== */
    
    public function __construct($name, $description, $text, $lang, $news) {
        $this->name = $name;
        $this->description = $description;
        $this->text = $text;
        $this->lang = $lang;
        $this->news = $news;
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
     * @return \PoradnikPiwny\Entities\NewsTranslation
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
     * @return \PoradnikPiwny\Entities\NewsTranslation
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
     * @return \PoradnikPiwny\Entities\NewsTranslation
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
     * @return \PoradnikPiwny\Entities\NewsTranslation
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
     * @return \PoradnikPiwny\Entities\NewsTranslation
     */
    public function setLang($lang) {
        $this->lang = $lang;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\News
     */
    public function getNews() {
        return $this->news;
    }

    /**
     * @param \PoradnikPiwny\Entities\News $news
     * @return \PoradnikPiwny\Entities\NewsTranslation
     */
    public function setNews($news) {
        $this->news = $news;
        
        return $this;
    }
}