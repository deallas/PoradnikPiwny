<?php

namespace PoradnikPiwny\Entities;

use Doctrine\ORM\Mapping as ORM,
    PoradnikPiwny\Entities\User,
    PoradnikPiwny\Entities\Beer;

/**
 * PoradnikPiwny\Entities\BeerComment
 *
 * @ORM\Table(name="beer_comments")
 * @ORM\Entity(repositoryClass="PoradnikPiwny\Entities\Repositories\BeerCommentsRepository")
 */
class BeerComment
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="BEECk_1_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="BEEC_Name", type="text", nullable=false)
     */
    protected $value;

    /**
     * @var string $useragent
     *
     * @ORM\Column(name="BEEC_Useragent", type="string", length=200, nullable=false)
     */
    protected $useragent;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="BEEC_Ip", type="string", length=39, nullable=false)
     */
    protected $ip;
    
    /**
     * @var \Zend_Date $dateAdded
     * 
     * @ORM\Column(name="BEEC_DateAdded", type="zenddate", nullable=false)
     */
    protected $dateAdded = null;
    
    /**
     * @var int $rankingTotal
     * 
     * @ORM\Column(name="BEEC_RankingTotal", type="integer", nullable=true) 
     */
    protected $rankingTotal = 0;
    
    /**
     * @var int $rankingCounter
     * 
     * @ORM\Column(name="BEE_RankingCounter", type="integer", nullable=true) 
     */
    protected $rankingCounter = 0;
    
    /**
     * @var int $status
     * 
     * @ORM\Column(name="BEEC_Status", type="smallint", nullable=false)
     */
    protected $status;
    
    /**
     * @var PoradnikPiwny\Entities\BeerComment $comment
     * 
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\BeerComment")
     * @ORM\JoinColumn(name="BEEC_1_ResponseId", referencedColumnName="BEECk_1_Id", onDelete="CASCADE")
     */    
    protected $response;
    
    /**
     * @var PoradnikPiwny\Entities\User $user
     * 
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\User", inversedBy="beerComments")
     * @ORM\JoinColumn(name="USR_2_Id", referencedColumnName="USRk_1_Id", onDelete="SET NULL")
     */
    protected $user;
    
    /**
     * @var PoradnikPiwny\Entities\Beer $beer
     * 
     * @ORM\ManyToOne(targetEntity="PoradnikPiwny\Entities\Beer", inversedBy="beerComments")
     * @ORM\JoinColumn(name="BEE_3_Id", referencedColumnName="BEEk_1_Id", onDelete="CASCADE")
     */
    protected $beer;
    
    /*====================================================== */
    
    /**
     * @param string $value
     * @param string $useragent
     * @param string $ip
     * @param int $rankingTotal
     * @param int $rankingCounter
     * @param int $status
     * @param \PoradnikPiwny\Entities\User $user
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param \PoradnikPiwny\Entities\BeerComment $response
     */
    public function __construct($value, $useragent, $ip,
                         $rankingTotal, $rankingCounter, $status, 
                         User $user, Beer $beer, $response = null) {
        $this->value = $value;
        $this->useragent = $useragent;
        $this->ip = $ip;
        $this->rankingTotal = $rankingTotal;
        $this->rankingCounter = $rankingCounter;
        $this->status = $status;
        $this->user = $user;
        $this->beer = $beer;
        $this->response = $response;
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
     * @return \PoradnikPiwny\Entities\BeerComment
     */
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

    public function getRankingTotal() {
        return $this->rankingTotal;
    }

    public function setRankingTotal($rankingTotal) {
        $this->rankingTotal = $rankingTotal;
        
        return $this;
    }

    public function getRankingCounter() {
        return $this->rankingCounter;
    }

    public function setRankingCounter($rankingCounter) {
        $this->rankingCounter = $rankingCounter;
        
        return $this;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
        
        return $this;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse($response) {
        $this->response = $response;
        
        return $this;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
        
        return $this;
    }

    public function getBeer() {
        return $this->beer;
    }

    public function setBeer($beer) {
        $this->beer = $beer;
        
        return $this;
    }
}
