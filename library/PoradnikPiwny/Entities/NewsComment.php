<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * PoradnikPiwny\Entities\NewsComment
 *
 * @ORM\Table(name="news_comments")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\NewsRepository")
 */
class NewsComment
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="NEWCk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;
   
    /**
     * @var string $value
     *
     * @ORM\Column(name="NEWC_Value", type="text", nullable=false)
     */
    protected $value;
    
    /**
     * @var string $useragent
     *
     * @ORM\Column(name="NEWC_Useragent", type="string", length=200, nullable=false)
     */
    protected $useragent;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="NEWC_Ip", type="string", length=39, nullable=false)
     */
    protected $ip;
    
    /**
     * @var \Zend_Date $dateAdded
     * 
     * @ORM\Column(name="NEWC_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded;
    
    /**
     * @var int $status
     * 
     * @ORM\Column(name="NEWC_Status", type="smallint", nullable=false)
     */
    protected $status;
    
    /**
     * @var PoradnikPiwny\Entities\User $user
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\User", inversedBy="newsComments")
     * @ORM\JoinColumn(name="USR_1_Id", referencedColumnName="USRk_1_Id", onDelete="SET NULL")
     */
    protected $user;

    /**
     * @var PoradnikPiwny\Entities\News $news
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\News", inversedBy="newsComments")
     * @ORM\JoinColumn(name="NEWS_2_Id", referencedColumnName="NEWSk_1_Id", onDelete="SET NULL")
     */
    protected $news;

    /**
     * @var PoradnikPiwny\Entities\NewsComment $newsCommentParent
     * 
     * @ORM\ManyToOne(targetEntity="\PoradnikPiwny\Entities\NewsComment", inversedBy="newsCommentChilds")
     * @ORM\JoinColumn(name="NEWC_3_Id_Parent", referencedColumnName="NEWCk_1_Id", onDelete="CASCADE")
     */
    protected $newsCommentParent;
    
    /**
     * @var ArrayCollection $newsCommentChilds
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\NewsComment", mappedBy="newsCommentParent", cascade={"persist"})
     */
    protected $newsCommentChilds;
    
    /*====================================================== */
    
    /**
     * 
     * @param string $value
     * @param string $useragent
     * @param string $ip
     * @param int $status
     * @param \PoradnikPiwny\Entities\User $user
     * @param \PoradnikPiwny\Entities\News $news
     * @param \PoradnikPiwny\Entities\NewsComment $newsCommentParent
     */
    public function __construct($value, $useragent, $ip, $status, 
                                $user, $news, $newsCommentParent = null) {
        $this->value = $value;
        $this->useragent = $useragent;
        $this->ip = $ip;
        $this->status = $status;
        $this->user = $user;
        $this->news = $news;
        $this->newsCommentParent = $newsCommentParent;
        
        $this->newsCommentChilds = new ArrayCollection();
    }
    
    /*====================================================== */   
    
    public function getNewsCommentChilds() {
        return $this->newsCommentChilds;
    }

    public function addNewsCommentChild($child) {
        $this->newsCommentChilds->add($child);
        
        return $this;
    }    
    
    /*====================================================== */
    
    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    public function getValue() {
        return $this->value;
    }

    public function setValue($value) {
        $this->value = $value;
        
        return $this;
    }

    public function getUseragent() {
        return $this->useragent;
    }

    public function setUseragent($useragent) {
        $this->useragent = $useragent;
        
        return $this;
    }

    public function getIp() {
        return $this->ip;
    }

    public function setIp($ip) {
        $this->ip = $ip;
        
        return $this;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function setDateAdded($dateAdded) {
        $this->dateAdded = $dateAdded;
        
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        
        return $this;
    }

    public function getNews() {
        return $this->news;
    }

    public function setNews($news) {
        $this->news = $news;
        
        return $this;
    }

    public function getNewsCommentParent() {
        return $this->newsCommentParent;
    }

    public function setNewsCommentParent($newsCommentParent) {
        $this->newsCommentParent = $newsCommentParent;
        
        return $this;
    }
}
