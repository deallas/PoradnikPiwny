<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\NewsTag
 *
 * @ORM\Table(name="NEWS_TAGS")
 * @ORM\Entity
 */
class NewsTag
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="NETGk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="NETG_Name", type="string", length=30, nullable=false)
     */
    protected $name;

    /*====================================================== */
    
    /**
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
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
     * @return \PoradnikPiwny\Entities\NewsTag
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
     * @return \PoradnikPiwny\Entities\NewsTag
     */
    public function setName($name) {
        $this->name = $name;
        
        return $this;
    }
}