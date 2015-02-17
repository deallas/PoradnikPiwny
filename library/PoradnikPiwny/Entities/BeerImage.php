<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\BeerImage
 *
 * @ORM\Table(name="beer_images")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerImagesRepository")
 */
class BeerImage
{
    const STATUS_WIDOCZNY = 'WIDOCZNY';
    const STATUS_NIEWIDOCZNY = 'NIEWIDOCZNY';
    
    /**
     * @var int $id
     *
     * @ORM\Id 
     * @ORM\Column(name="BEEIk_1_Id", type="integer", nullable=false)
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="BEEI_Title", type="string", length=200, nullable=false)
     */    
    protected $title;
    
    /**
     * @var string $path
     *
     * @ORM\Column(name="BEEI_Path", type="string", length=50, nullable=false)
     */
    protected $path;
    
    /**
     * @var int $position
     * 
     * @ORM\Column(name="BEEI_Position", type="integer") 
     */
    protected $position;
    
    /**
     * @var \Zend_Date $dateAdded
     * 
     * @ORM\Column(name="BEEI_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded = null;  
    
    /**
     * @var string
     * 
     * @ORM\Column(name="BEEI_Status", type="string", nullable=true)
     */
    protected $status = null;

    /**
     * @var PoradnikPiwny\Entities\Beer $beer
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\Beer", inversedBy="beerImages")
     * @ORM\JoinColumn(name="bee_1_id", referencedColumnName="BEEk_1_Id", onDelete="CASCADE")
     */
    protected $beer;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\Beer", mappedBy="image", cascade={"persist"})
     */
    protected $beers;
    
    /*====================================================== */
    
    /**
     * @param string $title
     * @param string $path
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param string $status
     * @param int $position
     */
    public function __construct($title, $path, $beer, $status = null, $position = null) 
    {
        $this->title = $title;
        $this->path = $path;
        $this->position = $position;
        $this->beer = $beer;
        $this->status = $status;
        
        $this->beers = new ArrayCollection();
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
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    public function setTitle($title) {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPath() {    
        return $this->path;
    }

    /**
     * @param string $path
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    public function setPath($path) {
        $this->path = $path;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition() {
        return $this->position;
    }

    /**
     * @param int $position
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    public function setPosition($position) {
        $this->position = $position;
        
        return $this;
    }
  
    /**
     * @return \Zend_Date
     */
    public function getDateAdded() {
        return $this->dateAdded;
    }

    /**
     * @param \Zend_Date $dateAdded
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
        
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
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    public function setBeer($beer) {
        $this->beer = $beer;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    public function setStatus($status) {
        $this->status = $status;
    
        return $this;
    }   
    
    /**
     * @param array $g_params
     * @return array
     */
    public function toArray($g_params = array()) {
        
        $a_params = array(
            'id',
            'title',
            'path',
            'position',
            'status',
            'dateAdded',
            'beer'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
}