<?php

namespace PoradnikPiwny\Entities\Repositories;

use PoradnikPiwny\Entities\BeerImage,
    PoradnikPiwny\Entities\Beer,
    Doctrine\ORM\NoResultException,
    WS\Tool;

class BeerImagesRepository extends AbstractPaginatorRepository
{   
    private static $_db_orders = array(
        'beer_image' => 'bi.position'
    ); 
    
    /* ---------------------------------------------------------------------- */
    
    public function getAvailableItems() {
        return array(10, 20, 30, 50);
    }

    public function getAvailableOrders() {
        return array(
            'beer_image'
        );
    }

    public function getDefaultOrders() {
        return array(
            'beer_image'
        );
    }
     
    public function getOptionsPaginatorName()
    {
        return 'beer_image_paginator';
    }
    
    public function getDefaultOrder()
    {
        return 'beer_image';
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param array $options
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @param boolean $showInvisible
     * @return \Zend_Paginator
     */
    public function getBeerImagesPaginator($options, $beer, $showInvisible = false) 
    {
        if(!isset(self::$_db_orders[$options['order']])) {
            $dbOrder = self::$_db_orders[$this->getDefaultOrder()];
        } else {
            $dbOrder = self::$_db_orders[$options['order']];
        }
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerImage', 'bi')
            ->leftJoin('bi.beer', 'b')
            ->where('b.id = :beerId')
            ->setParameter('beerId', $beer->getId())
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
     * @param \PoradnikPiwny\Entities\Beer $beer
     */
    public function addImage($title, $path, $primary, $status, $beer)
    {
        $bi = new BeerImage($title, $path, $beer, $status);        
        $this->_em->persist($bi);
        
        $m_bi = $beer->getImage();
        if($m_bi == null)
        {
            $beer->setImage($bi);
            $this->_em->persist($beer);
        }
        
        if($primary) {
            $this->setMainImage($bi, false);
        }
       
        $this->_em->flush();        
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerImage $bi
     * @param string $title
     * @param boolean $primary
     * @param boolean $status
     */
    public function editImage(BeerImage $bi, $title, $primary, $status)
    {
        $bi->setTitle($title)
           ->setStatus($status);
        
        if($primary) {
            $this->setMainImage($bi, false);
        }
       
        $this->_em->flush();        
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerImage $bi
     */
    public function removeImage(BeerImage $bi)
    {
        /*$beer = $bi->getBeer();
        $m_bi = $beer->getImage();
        if($m_bi->getId() == $bi->getId())
        {
            $beer->setImage($this->getFirstImageForBeer($beer, $bi->getId()));
            $this->_em->persist($beer);
        }*/
        $path = $bi->getPath();
        $id = $bi->getId();
        
        $this->_em->remove($bi);
        $this->_em->flush();
        
        @unlink(UPLOAD_PATH . '/images/' . $path);
        Tool::rmdirRecursive(UPLOAD_CACHE_PATH . '/images/' . $id);
    }
    
    public function moveUpImage(BeerImage $bi)
    {
        $position = $bi->getPosition();
        $bi->setPosition($position-1);
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerImage', 'bi')
            ->where('bi.position = :position')
            ->setParameter('position', $position-1)
            ->leftJoin('bi.beer', 'b')
            ->andWhere('b.id = :beerId')
            ->setParameter('beerId', $bi->getBeer()->getId());
        
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
    
    public function moveDownImage(BeerImage $bi)
    {
        $position = $bi->getPosition();
        $bi->setPosition($position+1);
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerImage', 'bi')
            ->where('bi.position = :position')
            ->setParameter('position', $position+1)
            ->leftJoin('bi.beer', 'b')
            ->andWhere('b.id = :beerId')
            ->setParameter('beerId', $bi->getBeer()->getId());
        
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
     * Zwraca zdjęcie piwa na postawie id
     * 
     * @param int $id
     * @param boolean $forceNull
     * @param boolean $ignoreInvisible
     * @return \PoradnikPiwny\Entities\BeerImage
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerImageNotFoundException
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
        
        /* @var $bi \PoradnikPiwny\Entities\BeerImage */
        $bi = $this->_em->find('\PoradnikPiwny\Entities\BeerImage', intval($id));
        
        if(!$bi)
        {
            throw new \PoradnikPiwny\Exception\BeerImageNotFoundException();
        }
        
        if(!$ignoreInvisible && ($bi->getStatus() == BeerImage::STATUS_NIEWIDOCZNY))
        {
            throw new \PoradnikPiwny\Exception\BeerImageNotFoundException();
        }
        
        return $bi;
    }
    
    /**
     * Zwraca pierwszy obrazek dla danego piwa
     * 
     * @param \PoradnikPiwny\Entities\Beer $beer
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    public function getFirstImageForBeer(Beer $beer, $excludeId = null)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerImage', 'bi')
            ->leftJoin('bi.beer', 'b')
            ->where('b.id = :beerId')
            ->setParameter('beerId', $beer->getId())
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
     * Zwraca następne zdjęcie w kolejce dla danego piwa
     * 
     * @param int $id
     * @param boolean $ignoreInvisible
     * @return \PoradnikPiwny\Entities\BeerImage
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerImageNotFoundException
     * @throws \PoradnikPiwny\Exception\BeerImageNeightborNotFoundException
     */
    public function getNextImageById($id, $ignoreInvisible = false)
    {
        return $this->_getNeightborForImage($id, $ignoreInvisible);
    }
    
    /**
     * Zwraca poprzednie zdjęcie w kolejce dla danego piwa
     * 
     * @param int $id
     * @param boolean $ignoreInvisible
     * @return \PoradnikPiwny\Entities\BeerImage
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerImageNotFoundException
     * @throws \PoradnikPiwny\Exception\BeerImageNeightborNotFoundException
     */
    public function getPrevImageById($id, $ignoreInvisible = false) 
    {
        return $this->_getNeightborForImage($id, $ignoreInvisible, false);
    }
    
    /**
     * Ustawia główne zdjęcie dla piwa
     * 
     * @param \PoradnikPiwny\Entities\BeerImage $image
     * @param boolean $autoFlush
     */
    public function setMainImage(BeerImage $image, $autoFlush = true)
    {
        $beer = $image->getBeer();
        $beer->setImage($image);
        
        $this->_em->persist($beer);
        
        if($autoFlush)
        {
            $this->_em->flush();
        }
    }
    
    /**
     * Ukrywa zdjęcie
     * 
     * @param \PoradnikPiwny\Entities\BeerImage $image
     * @param boolean $autoFlush
     */
    public function setInvisible(BeerImage $image, $autoFlush = true)
    {
        $image->setStatus(BeerImage::STATUS_NIEWIDOCZNY);    
        $this->_em->persist($image);
        
        if($autoFlush)
        {
            $this->_em->flush();
        }
    }
    
    /**
     * Zmienia status zdjęcia na widoczny
     * 
     * @param \PoradnikPiwny\Entities\BeerImage $image
     * @param boolean $autoFlush
     */
    public function setVisible(BeerImage $image, $autoFlush = true)
    {
        $image->setStatus(BeerImage::STATUS_WIDOCZNY); 
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
     * @return \PoradnikPiwny\Entities\BeerImage
     * @throws \PoradnikPiwny\Exception\NullPointerException
     * @throws \PoradnikPiwny\Exception\BeerImageNotFoundException
     * @throws \PoradnikPiwny\Exception\BeerImageNeightborNotFoundException
     */
    private function _getNeightborForImage($id, $ignoreInvisible = false, $next = true)
    {
        $bi = $this->getImageById($id);
        $beer = $bi->getBeer();
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bi'))
            ->from('\PoradnikPiwny\Entities\BeerImage', 'bi')
            ->leftJoin('bi.beer', 'b')
            ->where('b.id = :beerId')
            ->setParameter('beerId', $beer->getId())
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
            throw new \PoradnikPiwny\Exception\BeerImageNeightborNotFoundException();
        }
        
        if(!$ignoreInvisible && ($result->getStatus() == BeerImage::STATUS_NIEWIDOCZNY))
        {
            throw new \PoradnikPiwny\Exception\BeerImageNotFoundException();
        }
        
        return $result;        
    }
}