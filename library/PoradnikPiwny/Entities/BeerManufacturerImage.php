<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\BeerManufacturerImage
 *
 * @ORM\Table(name="beer_manufacturer_images")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerManufacturerImagesRepository")
 */
class BeerManufacturerImage
{
    const STATUS_WIDOCZNY = 'WIDOCZNY';
    const STATUS_NIEWIDOCZNY = 'NIEWIDOCZNY';
    
    /**
     * @var int $id
     *
     * @ORM\Id 
     * @ORM\Column(name="BEMIk_1_Id", type="integer", nullable=false)
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="BEMI_Title", type="string", length=200, nullable=false)
     */    
    protected $title;
    
    /**
     * @var string $path
     *
     * @ORM\Column(name="BEMI_Path", type="string", length=50, nullable=false)
     */
    protected $path;
    
    /**
     * @var int $position
     * 
     * @ORM\Column(name="BEMI_Position", type="integer") 
     */
    protected $position;
    
    /**
     * @var \Zend_Date $dateAdded
     * 
     * @ORM\Column(name="BEMI_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded = null;  
    
    /**
     * @var string
     * 
     * @ORM\Column(name="BEMI_Status", type="string", nullable=true)
     */
    protected $status = null;

    /**
     * @var PoradnikPiwny\Entities\Beer $beer
     *
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\BeerManufacturer", inversedBy="beerManufacturerImages")
     * @ORM\JoinColumn(name="beem_1_id", referencedColumnName="BEEMk_1_Id", onDelete="CASCADE")
     */
    protected $beerManufacturer;
    
    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerManufacturer", mappedBy="beerManufacturerImage", cascade={"persist"})
     */
    protected $beerManufacturers;
    
    /*====================================================== */
    
    /**
     * @param string $title
     * @param string $path
     * @param \PoradnikPiwny\Entities\BeerManufacturer $beerManufacturer
     * @param string $status
     * @param int $position
     */
    public function __construct($title, $path, $beerManufacturer, $status = null, $position = null) 
    {
        $this->title = $title;
        $this->path = $path;
        $this->position = $position;
        $this->beerManufacturer = $beerManufacturer;
        $this->status = $status;
        
        $this->beerManufacturers = new ArrayCollection();
    }
    
    /*====================================================== */
    
    public function getBeerManufacturers() {
        return $this->beerManufacturers;
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
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
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
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
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
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
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
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
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
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     */
    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
        
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
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     */
    public function setStatus($status) {
        $this->status = $status;
        
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
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     */
    public function setBeerManufacturer($beerManufacturer) {
        $this->beerManufacturer = $beerManufacturer;
        
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
            'beerManufacturer'
        );
        
        return Tool::getParamsFromEntity($this, $a_params, $g_params);
    }
}