<?php

namespace PoradnikPiwny\Entities\Proxies\__CG__\PoradnikPiwny\Entities;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Beer extends \PoradnikPiwny\Entities\Beer implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function addBeerRanking(\PoradnikPiwny\Entities\BeerRanking $ranking)
    {
        $this->__load();
        return parent::addBeerRanking($ranking);
    }

    public function getBeerRankings()
    {
        $this->__load();
        return parent::getBeerRankings();
    }

    public function addBeerImage(\PoradnikPiwny\Entities\BeerImage $image)
    {
        $this->__load();
        return parent::addBeerImage($image);
    }

    public function getBeerImages()
    {
        $this->__load();
        return parent::getBeerImages();
    }

    public function addBeerFavorite(\PoradnikPiwny\Entities\BeerFavorite $fav)
    {
        $this->__load();
        return parent::addBeerFavorite($fav);
    }

    public function getBeerFavorites()
    {
        $this->__load();
        return parent::getBeerFavorites();
    }

    public function addBeerComment(\PoradnikPiwny\Entities\BeerComment $comment)
    {
        $this->__load();
        return parent::addBeerComment($comment);
    }

    public function getBeerComments()
    {
        $this->__load();
        return parent::getBeerComments();
    }

    public function addBeerTranslation(\PoradnikPiwny\Entities\BeerTranslation $translation)
    {
        $this->__load();
        return parent::addBeerTranslation($translation);
    }

    public function getBeerTranslations()
    {
        $this->__load();
        return parent::getBeerTranslations();
    }

    public function addBeerPrice(\PoradnikPiwny\Entities\BeerPrice $price)
    {
        $this->__load();
        return parent::addBeerPrice($price);
    }

    public function getBeerPrices()
    {
        $this->__load();
        return parent::getBeerPrices();
    }

    public function addBeerReview(\PoradnikPiwny\Entities\BeerReview $review)
    {
        $this->__load();
        return parent::addBeerReview($review);
    }

    public function getBeerReviews()
    {
        $this->__load();
        return parent::getBeerReviews();
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function setId($id)
    {
        $this->__load();
        return parent::setId($id);
    }

    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function setName($name)
    {
        $this->__load();
        return parent::setName($name);
    }

    public function getUrlName()
    {
        $this->__load();
        return parent::getUrlName();
    }

    public function setUrlName($urlName)
    {
        $this->__load();
        return parent::setUrlName($urlName);
    }

    public function getAlcohol()
    {
        $this->__load();
        return parent::getAlcohol();
    }

    public function setAlcohol($alcohol)
    {
        $this->__load();
        return parent::setAlcohol($alcohol);
    }

    public function getExtract()
    {
        $this->__load();
        return parent::getExtract();
    }

    public function setExtract($extract)
    {
        $this->__load();
        return parent::setExtract($extract);
    }

    public function getMalt()
    {
        $this->__load();
        return parent::getMalt();
    }

    public function setMalt($malt)
    {
        $this->__load();
        return parent::setMalt($malt);
    }

    public function getFamily()
    {
        $this->__load();
        return parent::getFamily();
    }

    public function setFamily($family)
    {
        $this->__load();
        return parent::setFamily($family);
    }

    public function getType()
    {
        $this->__load();
        return parent::getType();
    }

    public function setType($type)
    {
        $this->__load();
        return parent::setType($type);
    }

    public function getFiltered()
    {
        $this->__load();
        return parent::getFiltered();
    }

    public function setFiltered($filtered)
    {
        $this->__load();
        return parent::setFiltered($filtered);
    }

    public function getPasteurized()
    {
        $this->__load();
        return parent::getPasteurized();
    }

    public function setPasteurized($pasteurized)
    {
        $this->__load();
        return parent::setPasteurized($pasteurized);
    }

    public function getFlavored()
    {
        $this->__load();
        return parent::getFlavored();
    }

    public function setFlavored($flavored)
    {
        $this->__load();
        return parent::setFlavored($flavored);
    }

    public function getPlaceofbrew()
    {
        $this->__load();
        return parent::getPlaceofbrew();
    }

    public function setPlaceofbrew($placeofbrew)
    {
        $this->__load();
        return parent::setPlaceofbrew($placeofbrew);
    }

    public function getRankingTotal()
    {
        $this->__load();
        return parent::getRankingTotal();
    }

    public function setRankingTotal($rankingTotal)
    {
        $this->__load();
        return parent::setRankingTotal($rankingTotal);
    }

    public function getRankingCounter()
    {
        $this->__load();
        return parent::getRankingCounter();
    }

    public function setRankingCounter($rankingCounter)
    {
        $this->__load();
        return parent::setRankingCounter($rankingCounter);
    }

    public function getRankingAvg()
    {
        $this->__load();
        return parent::getRankingAvg();
    }

    public function setRankingAvg($rankingAvg)
    {
        $this->__load();
        return parent::setRankingAvg($rankingAvg);
    }

    public function getRankingWeightedAvg()
    {
        $this->__load();
        return parent::getRankingWeightedAvg();
    }

    public function setRankingWeightedAvg($rankingWeightedAvg)
    {
        $this->__load();
        return parent::setRankingWeightedAvg($rankingWeightedAvg);
    }

    public function getDistributor()
    {
        $this->__load();
        return parent::getDistributor();
    }

    public function setDistributor($distributor)
    {
        $this->__load();
        return parent::setDistributor($distributor);
    }

    public function getManufacturer()
    {
        $this->__load();
        return parent::getManufacturer();
    }

    public function setManufacturer($manufacturer)
    {
        $this->__load();
        return parent::setManufacturer($manufacturer);
    }

    public function getCountry()
    {
        $this->__load();
        return parent::getCountry();
    }

    public function setCountry($country)
    {
        $this->__load();
        return parent::setCountry($country);
    }

    public function getRegion()
    {
        $this->__load();
        return parent::getRegion();
    }

    public function setRegion($region)
    {
        $this->__load();
        return parent::setRegion($region);
    }

    public function getCity()
    {
        $this->__load();
        return parent::getCity();
    }

    public function setCity($city)
    {
        $this->__load();
        return parent::setCity($city);
    }

    public function getImage()
    {
        $this->__load();
        return parent::getImage();
    }

    public function setImage($image)
    {
        $this->__load();
        return parent::setImage($image);
    }

    public function getStatus()
    {
        $this->__load();
        return parent::getStatus();
    }

    public function setStatus($status)
    {
        $this->__load();
        return parent::setStatus($status);
    }

    public function getDateAdded()
    {
        $this->__load();
        return parent::getDateAdded();
    }

    public function setDateAdded($dateAdded)
    {
        $this->__load();
        return parent::setDateAdded($dateAdded);
    }

    public function toArray($g_params = array (
))
    {
        $this->__load();
        return parent::toArray($g_params);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'name', 'urlName', 'alcohol', 'extract', 'malt', 'type', 'filtered', 'pasteurized', 'flavored', 'placeofbrew', 'rankingTotal', 'rankingCounter', 'rankingAvg', 'rankingWeightedAvg', 'dateAdded', 'status', 'family', 'distributor', 'manufacturer', 'country', 'region', 'city', 'image', 'beerRankings', 'beerImages', 'beerFavorites', 'beerComments', 'beerTranslations', 'beerPrices', 'beerReviews', 'beerSearchConnections');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields as $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}