<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    PoradnikPiwny\Security,
    WS\Tool;

/**
 * PoradnikPiwny\Entities\User
 *
 * @ORM\Table(name="USERS")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\UsersRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User
{   
    const STATUS_INACTIVE = 'INACTIVE';
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_BANNED = 'BANNED';
    
    /**
     * @var int $id
     *
     * @ORM\Column(name="USRk_1_Id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $username
     * 
     * @ORM\Column(name="USR_Username", type="string", length=50, nullable=false)
     */
    protected $username;
    
    /**
     * @var string $email
     *
     * @ORM\Column(name="USR_Email", type="string", length=50, nullable=false)
     */
    protected $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="USR_Password", type="string", length=64, nullable=false)
     */
    protected $password;
    
    /**
     * @var string $visibleName
     * 
     * @ORM\Column(name="USR_VisibleName", type="string", length=50, nullable=false)
     */
    protected $visibleName;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="USR_Salt", type="string", length=10, nullable=false)
     */
    protected $salt;

    /**
     * @var string $status
     * 
     * @ORM\Column(name="USR_Status", type="string", nullable=true) 
     */    
    protected $status;
    
    /**
     * @var \PoradnikPiwny\Entities\AclRole $role
     * 
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\AclRole", inversedBy="users")
     * @ORM\JoinColumn(name="ROL_1_Id", referencedColumnName="ROLk_1_Id", onDelete="CASCADE")
     */
    protected $role;
    
    /**
     * @var ArrayCollection $beerRankings
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerRanking", mappedBy="user") 
     */
    protected $beerRankings;
    
    /**
     * @var ArrayCollection $beerFavorites
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerFavorite", mappedBy="user") 
     */
    protected $beerFavorites;
    
    /**
     * @var ArrayCollection $beerComments
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerComment", mappedBy="user", cascade={"persist"})
     */
    protected $beerComments;
    
    /**
     * @var ArrayCollection $beerReviews
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerReview", mappedBy="user", cascade={"persist"})
     */
    protected $beerReviews;
    
    /**
    * @var ArrayCollection $userMetas
    * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\UserMeta", mappedBy="user", cascade={"persist"})
    */
    protected $userMetas;
    
    /**
     * @var ArrayCollection $userActivations
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\UserActivation", mappedBy="user", cascade={"persist"})
     */   
    protected $userActivations;

    /**
     * @var ArrayCollection $userLogs
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\UserLog", mappedBy="user", cascade={"persist"})
     */
    protected $userLogs;
    
    /**
     *
     * @var ArrayCollection $news
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\News", mappedBy="user", cascade={"persist"})
     */
    protected $news;
    
    /**
     * @var ArrayCollection $newsComments
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\NewsComment", mappedBy="user", cascade={"persist"})
     */    
    protected $newsComments;
    
    /**
     * @var ArrayCollection $beerReviewRankings
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\BeerReviewRanking", mappedBy="user", cascade={"persist"})
     */
    protected $beerReviewRankings;
    
    /**
     * @var ArrayCollection $sessions
     * @ORM\OneToMany(targetEntity="PoradnikPiwny\Entities\Session", mappedBy="user", cascade={"persist"})
     */    
    protected $sessions;
    
    /**
     * @var ArrayCollection $beerSearches
     * @ORM\OneToMany(targetEntity="\PoradnikPiwny\Entities\BeerSearch", mappedBy="user", cascade={"persist"})
     */
    protected $beerSearches;
    
    /*====================================================== */
    
    /**
     * @param string $username
     * @param string $visibleName
     * @param string $email
     * @param string $password
     * @param \PoradnikPiwny\Entities\AclRole $role
     * @param string $status
     */
    public function __construct($username, $visibleName, $email, 
                                 $password, $role, $status = null)
    {
        $this->username = $username;
        $this->visibleName = $visibleName;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        
        if($status != null) {
            $this->status = $status;
        } else {
            $this->status = self::STATUS_ACTIVE;
        }
        
        $this->beerRankings = new ArrayCollection();
        $this->beerFavorites = new ArrayCollection();
        $this->beerComments = new ArrayCollection();
        $this->beerReview = new ArrayCollection();
        $this->beerReviewRankings = new ArrayCollection();
        $this->beerSearches = new ArrayCollection();
        
        $this->userMetas = new ArrayCollection();
        $this->userActivations = new ArrayCollection();
        $this->userLogs = new ArrayCollection();
        
        $this->news = new ArrayCollection();
        $this->newsComments = new ArrayCollection();
        
        $this->sessions = new ArrayCollection();
    }
    
    /*====================================================== */
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if($this->salt == null) {
            $this->salt = Tool::randomString(10);
        }
        
        $this->password = Security::getInstance()->createPassword($this->password . $this->salt);
    }

    /*====================================================== */

    /**
     * @param \PoradnikPiwny\Entities\UserMeta $metadata
     */
    public function addMetadata(UserMeta $metadata)
    {
        $this->userMetas->add($metadata);
    }
    
    /**
     * @return \ArrayIterator
     */
    public function getMetadatas()
    {
        return $this->userMetas->getIterator();
    }

    /**
     * @return array
     */
    public function getMetadataArray()
    {
        $data = array();
        $it = $this->userMetas->getIterator();
        while($it->valid())
        {
            $meta = $it->current();
            $data[$meta->getKey()] = $meta->getValue();
            $it->next();
        }
        
        return $data;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerRanking $ranking
     */
    public function addBeerRanking(BeerRanking $ranking)
    {
        $this->rankings->add($ranking);
    }
    
    /**
     * @return \ArrayIterator
     */
    public function getBeerRankings()
    {
        return $this->rankings->getIterator();
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerFavorite $fav
     * @return \PoradnikPiwny\Entities\User
     */
    public function addBeerFavorite(BeerFavorite $fav)
    {
        $this->beerFavorites->add($fav);
        
        return $this;
    }
    
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerFavorites() {
        return $this->beerFavorites;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerComment $com
     * @return \PoradnikPiwny\Entities\User
     */
    public function addBeerComment(BeerComment $com)
    {
        $this->beerComments->add($com);
        
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getBeerComments() {
        return $this->beerComments;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerReview $br
     * @return \PoradnikPiwny\Entities\User
     */
    public function addBeerReview(BeerReview $br)
    {
        $this->beerReviews->add($br);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getBeerReviews()
    {
        return $this->beerReviews;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerReviewRanking $brr
     * @return \PoradnikPiwny\Entities\User
     */
    public function addBeerReviewRanking(BeerReviewRanking $brr)
    {
        $this->beerReviewRankings->add($brr);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getBeerReviewRankings()
    {
        return $this->beerReviewRankings;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\UserActivation $activation
     * @return \PoradnikPiwny\Entities\User
     */
    public function addUserActivation(UserActivation $activation)
    {
        $this->userActivations->add($activation);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getUserActivations()
    {
        return $this->userActivations;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\UserLog $log
     * @return \PoradnikPiwny\Entities\User
     */
    public function addUserLog(UserLog $log)
    {
        $this->userLogs->add($log);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getUserLogs()
    {
        return $this->userLogs;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\News $news
     * @return \PoradnikPiwny\Entities\User
     */
    public function addNews(News $news)
    {
        $this->news->add($news);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getAllNews()
    {
        return $this->news;
    }
    
    /**
     * @param \PoradnikPiwny\Entities\NewsComment $nc
     * @return \PoradnikPiwny\Entities\User
     */
    public function addNewsComment(NewsComment $nc)
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
     * @param \PoradnikPiwny\Entities\Session $session
     * @return \PoradnikPiwny\Entities\User
     */
    public function addSession(Session $session)
    {
        $this->sessions->add($session);
        
        return $this;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getSessions()
    {
        return $this->sessions;
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
     * @return \PoradnikPiwny\Entities\User
     */
    public function setId($id) {
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }
    
    /**
     * @param string $username
     * @return \PoradnikPiwny\Entities\User
     */
    public function setUsername($username) {
        $this->username = $username;
        
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
     * @return \PoradnikPiwny\Entities\User
     */
    public function setEmail($email) {
        $this->email = $email;         
        
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param string $password
     * @return \PoradnikPiwny\Entities\User
     */
    public function setPassword($password) {
        $this->password = $password;        
        
        return $this;
    }

    /**
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * @param string $salt
     * @return \PoradnikPiwny\Entities\User
     */
    public function setSalt($salt) {
        $this->salt = $salt;        
        
        return $this;
    }

    /**
     * @return \PoradnikPiwny\Entities\AclRole
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * @param \PoradnikPiwny\Entities\AclRole $role
     * @return \PoradnikPiwny\Entities\User
     */
    public function setRole($role) {
        $this->role = $role;        
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getVisibleName() {
        return $this->visibleName;
    }

    /**
     * @param string $visibleName
     * @return \PoradnikPiwny\Entities\User
     */
    public function setVisibleName($visibleName) {
        $this->visibleName = $visibleName;
        
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
     * @return \PoradnikPiwny\Entities\User
     */
    public function setStatus($status) {
        $this->status = $status;
    
        return $this;
    }
}