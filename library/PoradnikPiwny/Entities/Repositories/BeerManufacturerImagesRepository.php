<?php

namespace PoradnikPiwny\Entities\Repositories;

use PoradnikPiwny\Entities\BeerManufacturerImage,
    PoradnikPiwny\Entities\BeerManufacturer,
    Doctrine\ORM\NoResultException,
    WS\Tool;

class BeerManufacturerImagesRepository extends AbstractPaginatorRepository
{   
    private static $_db_orders = array(
        'beer_manufacturer_image' => 'bi.position'
    ); 
    
    /* ---------------------------------------------------------------------- */
    
    public function getAvailableItems() {
        return array(10, 20, 30, 50);
    }

    public function getAvailableOrders() {
        return array(
            'beer_manufacturer_image'
        );
    }

    public function getDefaultOrders() {
        return array(
            'beer_manufacturer_image'
        );
    }
     
    public function getOptionsPaginatorName()
    {
        return 'beer_man_image_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'beer_manufacturer_image';
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param array $options
     * @param \PoradnikPiwny\Entities\BeerManufacturer $beerMan
     * @param boolean $showInvisible
     * @return \Zend_Paginator
     */
    public function getBeerManufacturerImagesPaginator($options, $beerMan, $showInvisible = false) 
    {
        if(!isset(self::$_db_orders[$options['order']])) {
            $dbOrder = self::$_db_orders[$this->getDefaultOrder()];
        } else {
            $dbOrder = self::$_db_orders[$options['order']];
        }
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerManufacturerImage', 'bi')
            ->leftJoin('bi.beerManufacturer', 'bm')
            ->where('bm.id = :beerManufacturerId')
            ->setParameter('beerManufacturerId', $beerMan->getId())
            ->orderBy($dbOrder, 'ASC');
        
        if(!$showInvisible) {
            $qb->andWhere('bi.status = :status')
               ->setParameter('status', BeerImage::STATUS_WIDOCZNY);
        }
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($options['page']);
        $paginator->setItemCountPerPage($options['items']);
        
        return $paginator;
    }
    
    /**
     * @param string $title
     * @param string $path
     * @param boolean $primary
     * @param boolean $status
     * @param \PoradnikPiwny\Entities\BeerManufacturer $beer
     */
    public function addImage($title, $path, $primary, $status, $beerMan)
    {
        $bi = new BeerManufacturerImage($title, $path, $beerMan, $status);        
        $this->_em->persist($bi);
        
        $m_bi = $beerMan->getBeerManufacturerImage();
        if($m_bi == null)
        {
            $beerMan->setBeerManufacturerImage($bi);
            $this->_em->persist($beerMan);
        }
        
        if($primary) {
            $this->setMainImage($bi, false);
        }
       
        $this->_em->flush();        
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerManufacturerImage $bi
     * @param string $title
     * @param boolean $primary
     * @param boolean $status
     */
    public function editImage(BeerManufacturerImage $bi, $title, $primary, $status)
    {
        $bi->setTitle($title)
           ->setStatus($status);
        
        if($primary) {
            $this->setMainImage($bi, false);
        }
       
        $this->_em->flush();        
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerManufacturerImage $bi
     */
    public function removeImage(BeerManufacturerImage $bi)
    {
        $path = $bi->getPath();
        $id = $bi->getId();
        
        $this->_em->remove($bi);
        $this->_em->flush();
        
        @unlink(UPLOAD_PATH . '/images/' . $path);
        Tool::rmdirRecursive(UPLOAD_CACHE_PATH . '/images/' . $id);
    }
    
    public function moveUpImage(BeerManufacturerImage $bi)
    {
        $position = $bi->getPosition();
        $bi->setPosition($position-1);
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerManufacturerImage', 'bi')
            ->where('bi.position = :position')
            ->setParameter('position', $position-1)
            ->leftJoin('bi.beerManufacturer', 'bm')
            ->andWhere('bm.id = :beerManufacturerId')
            ->setParameter('beerManufacturerId', $bi->getBeerManufacturer()->getId());
        
        try {
            $bi2 = $qb->getQuery()->getSingleResult();
            $bi2->setPosition($position);
            
            $this->_em->persist($bi);
            $this->_em->persist($bi2);
            $this->_em->flush();            
        } catch(NoResultException $ex) {
            return null;
        }
    }
    
    public function moveDownImage(BeerManufacturerImage $bi)
    {
        $position = $bi->getPosition();
        $bi->setPosition($position+1);
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerManufacturerImage', 'bi')
            ->where('bi.position = :position')
            ->setParameter('position', $position+1)
            ->leftJoin('bi.beerManufacturer', 'bm')
            ->andWhere('bm.id = :beerManufacturerId')
            ->setParameter('beerManufacturerId', $bi->getBeerManufacturer()->getId());
        
        try {
            $bi2 = $qb->getQuery()->getSingleResult();
            $bi2->setPosition($position);
            
            $this->_em->persist($bi);
            $this->_em->persist($bi2);
            $this->_em->flush();            
        } catch(NoResultException $ex) {
            return null;
        }     
    }
    
    /**
     * Zwraca zdjęcie wytwórcy na postawie id
     * 
     * @param int $id
     * @param boolean $forceNull
     * @param boolean $ignoreInvisible
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerManufacturerImageNotFoundException
     */
    public function getImageById($id, $forceNull = false, $ignoreInvisible = false)
    {
        if(!\Zend_Validate::is($id, 'NotEmpty'))
        {
            if($forceNull) {
                return null;
            } else {
                throw new \PoradnikPiwny\Exception\NullPointerException();
            }
        }
        
        /* @var $bi \PoradnikPiwny\Entities\BeerManufacturerImage */
        $bi = $this->_em->find('\PoradnikPiwny\Entities\BeerManufacturerImage', intval($id));
        
        if(!$bi)
        {
            throw new \PoradnikPiwny\Exception\BeerManufacturerImageNotFoundException();
        }
        
        if(!$ignoreInvisible && ($bi->getStatus() == BeerManufacturerImage::STATUS_NIEWIDOCZNY))
        {
            throw new \PoradnikPiwny\Exception\BeerManufacturerImageNotFoundException();
        }
        
        return $bi;
    }
    
    /**
     * Zwraca pierwszy obrazek dla danego wytwórcy
     * 
     * @param \PoradnikPiwny\Entities\BeerManufacturer $beerMan
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     */
    public function getFirstImageForBeer(BeerManufacturer $beerMan, $excludeId = null)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerManufacturerImage', 'bi')
            ->leftJoin('bi.beerManufacturer', 'bm')
            ->where('bm.id = :beerManufacturerId')
            ->setParameter('beerManufacturerId', $beerMan->getId())
            ->orderBy('bi.position');
        
        if($excludeId != null)
        {
            $qb->andWhere('bi.id != :id')
               ->setParameter('id', $excludeId);
        }
        
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch(NoResultException $exc) {
            return null;
        }
        
        return $result;
    }
    
    /**
     * Zwraca następne zdjęcie w kolejce dla danego wytwórcy
     * 
     * @param int $id
     * @param boolean $ignoreInvisible
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerManufacturerImageNotFoundException
     * @throws \PoradnikPiwny\Exception\BeerManufacturerImageNeightborNotFoundException
     */
    public function getNextImageById($id, $ignoreInvisible = false)
    {
        return $this->_getNeightborForImage($id, $ignoreInvisible);
    }
    
    /**
     * Zwraca poprzednie zdjęcie w kolejce dla danego wytwórcy
     * 
     * @param int $id
     * @param boolean $ignoreInvisible
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerManufacturerImageNotFoundException
     * @throws \PoradnikPiwny\Exception\BeerManufacturerImageNeightborNotFoundException
     */
    public function getPrevImageById($id, $ignoreInvisible = false) 
    {
        return $this->_getNeightborForImage($id, $ignoreInvisible, false);
    }
    
    /**
     * Ustawia główne zdjęcie dla wytwórcy
     * 
     * @param \PoradnikPiwny\Entities\BeerManufacturerImage $image
     * @param boolean $autoFlush
     */
    public function setMainImage(BeerManufacturerImage $image, $autoFlush = true)
    {
        $beer = $image->getBeerManufacturer();
        $beer->setBeerManufacturerImage($image);
        
        $this->_em->persist($beer);
        
        if($autoFlush)
        {
            $this->_em->flush();
        }
    }
    
    /**
     * Ukrywa zdjęcie
     * 
     * @param \PoradnikPiwny\Entities\BeerManufacturerImage $image
     * @param boolean $autoFlush
     */
    public function setInvisible(BeerManufacturerImage $image, $autoFlush = true)
    {
        $image->setStatus(BeerManufacturerImage::STATUS_NIEWIDOCZNY);    
        $this->_em->persist($image);
        
        if($autoFlush)
        {
            $this->_em->flush();
        }
    }
    
    /**
     * Zmienia status zdjęcia na widoczny
     * 
     * @param \PoradnikPiwny\Entities\BeerManufacturerImage $image
     * @param boolean $autoFlush
     */
    public function setVisible(BeerManufacturerImage $image, $autoFlush = true)
    {
        $image->setStatus(BeerManufacturerImage::STATUS_WIDOCZNY); 
        $this->_em->persist($image);
        
        if($autoFlush)
        {
            $this->_em->flush();
        }
    }
    
    /**
     * @param int $id
     * @param boolean $ignoreInvisible
     * @param boolean $next
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerManufacturerImageNotFoundException
     * @throws \PoradnikPiwny\Exception\BeerManufacturerImageNeightborNotFoundException
     */
    private function _getNeightborForImage($id, $ignoreInvisible = false, $next = true)
    {
        $bi = $this->getImageById($id);
        $beerManufacturer = $bi->getBeerManufacturer();
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerManufacturerImage', 'bi')
            ->leftJoin('bi.beerManufacturer', 'bm')
            ->where('bm.id = :beerManufacturerId')
            ->setParameter('beerManufacturerId', $beerManufacturer->getId())
            ->orderBy('bi.position', 'DESC')
            ->setMaxResults(1);
        
        if($next) {
            $qb->andWhere('bi.position > :position');
        } else {
            $qb->andWhere('bi.position < :position');
        }
        $qb->setParameter('position', $bi->getPosition());
        
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch(NoResultException $exc) {
            throw new \PoradnikPiwny\Exception\BeerManufacturerImageNeightborNotFoundException();
        }
        
        if(!$ignoreInvisible && ($result->getStatus() == BeerManufacturerImage::STATUS_NIEWIDOCZNY))
        {
            throw new \PoradnikPiwny\Exception\BeerManufacturerImageNotFoundException();
        }
        
        return $result;        
    }
}