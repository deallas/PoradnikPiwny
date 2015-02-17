<?php

namespace PoradnikPiwny\Entities\Repositories;

use \Doctrine\ORM\EntityRepository,
    \PoradnikPiwny\Entities\Newsletter,
    \Doctrine\ORM\NoResultException;

class NewsletterRepository extends EntityRepository
{   
    /**
     * @param int $page
     * @param int $itemCountPerPage
     * @param string $order
     * @param string $desc
     * @return \Zend_Paginator
     */
    public function getPaginator($page, $itemCountPerPage, $order, $desc = null) 
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('n'))
            ->from('\PoradnikPiwny\Entities\Newsletter', 'n')
            ->orderBy($order, ($desc == null) ? 'ASC' : 'DESC');
        
        $adapter = new \WS\Doctrine\Paginator($qb);
        
        $paginator = new \Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($itemCountPerPage);
        
        return $paginator;
    }
    
    /**
     * @param string $email
     * @param int $status
     * @param \Zend_Date $dateAdded
     */
    public function addNewsletter($email, 
                                  $status = Newsletter::NEWSLETTER_INACTIVE,
                                  $dateAdded = null)
    {    
        if($dateAdded == null)
        {
            $dateAdded = new \Zend_Date();
        }
        
        $newsletter = new Newsletter($email, $status, $dateAdded);
        
        $this->_em->persist($newsletter);
        $this->_em->flush();
    }

    /**
     * @param string $email
     * @return \PoradnikPiwny\Entities\Newsletter
     */
    public function getNewsletterByEmail($email)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('n')
            ->from('\PoradnikPiwny\Entities\Newsletter', 'n')
            ->where('n.email=:email')
            ->setParameter('email', $email);
 
        try {
            $result = $qb->getQuery()->getSingleResult();
        } catch(NoResultException $exc) {
            return null;
        }
        
        return $result;
    }
}
