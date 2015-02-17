<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * PoradnikPiwny\Entities\Newsletter
 *
 * @ORM\Table(name="NEWSLETTER")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\NewsletterRepository")
 */
class Newsletter
{
    const STATUS_NIEAKTYWNY = 'NIEAKTYWNY';
    const STATUS_AKTYWNY = 'AKTYWNY';
    const STATUS_USUNIETY = 'USUNIETY';  
    
    /**
     * @var int $id
     *
     * @ORM\Column(name="NEWLk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $email
     * 
     * @ORM\Column(name="NEWL_Email", length=200, type="string", nullable=false)
     */
    protected $email;
    
    /**
     * @var \Zend_Date $dateAdded
     * 
     * @ORM\Column(name="NEWL_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded;
    
    /**
     * @var string $status
     * 
     * @ORM\Column(name="NEWL_Status", type="string", nullable=false)
     */
    protected $status;
    
    /*====================================================== */
    
    /**
     * @param string $email
     * @param int $status
     */
    public function __construct($email, $status) 
    {
        $this->email = $email;
        $this->status = $status;
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
     * @return \PoradnikPiwny\Entities\Newsletter
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     * @return \PoradnikPiwny\Entities\Newsletter
     */
    public function setEmail($email) {
        $this->email = $email;
        
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
     * @return \PoradnikPiwny\Entities\Newsletter
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
     * @return \PoradnikPiwny\Entities\Newsletter
     */
    public function setStatus($status) {
        $this->status = $status;
        
        return $this;
    }
}
