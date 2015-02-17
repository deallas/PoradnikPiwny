<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * PoradnikPiwny\Entities\News
 *
 * @ORM\Table(name="NEWS")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\NewsRepository")
 */
class News
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="NEWSk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var \Zend_Date $dateAdded
     * 
     * @ORM\Column(name="NEWS_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded;
    
    /**
     * @var int $status
     * 
     * @ORM\Column(name="NEWS_Status", type="smallint", nullable=false)
     */
    protected $status;
    
    /**
     * @var \PoradnikPiwny\Entities\User $user
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\User", inversedBy="news")
     * @ORM\JoinColumn(name="USR_1_Id", referencedColumnName="USRk_1_Id", onDelete="SET NULL")
     */
    protected $user;
    
    /**
     * @var ArrayCollection $newsComments
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\NewsComment", mappedBy="news", cascade={"persist"})
     */
    protected $newsComments;
    
    /**
     * @var ArrayCollection $newsTranslations
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\NewsTranslation", mappedBy="news", cascade={"persist"})
     */
    protected $newsTranslations;

    /*====================================================== */
    
    /**
     * @param int $status
     * @param \PoradnikPiwny\Entities\User $user
     */
    public function __construct($status, $user) {
        $this->status = $status;
        $this->user = $user;
        
        $this->newsComments = new ArrayCollection();
        $this->newsTranslations = new ArrayCollection();
    }
    
    /*====================================================== */

    /**
     * @param \PoradnikPiwny\Entities\NewsComment $nc
     * @return \PoradnikPiwny\Entities\News
     */
    public function addNewsComment($nc)
    {
        $this->newsComments->add($nc);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getNewsComments()
    {
        return $this->newsComments;
    }
    
        /**
     * @param \PoradnikPiwny\Entities\NewsTranslation $nt
     * @return \PoradnikPiwny\Entities\News
     */
    public function addNewsTranslation($nt)
    {
        $this->newsTranslations->add($nt);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getNewsTranslations()
    {
        return $this->newsTranslations;
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
     * @return \PoradnikPiwny\Entities\News
     */
    public function setId($id) {
        $this->id = $id;
        
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
     * @return \PoradnikPiwny\Entities\News
     */
    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
        
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param int $status
     * @return \PoradnikPiwny\Entities\News
     */
    public function setStatus($status) {
        $this->status = $status;
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param \PoradnikPiwny\Entities\User $user
     * @return \PoradnikPiwny\Entities\News
     */
    public function setUser($user) {
        $this->user = $user;
        
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments() {
        return $this->comments;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\NewsComment $comment
     * @return \PoradnikPiwny\Entities\News
     */
    public function addComment($comment) {
        $this->comments->add($comment);
        
        return $this;
    }
}
