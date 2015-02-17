<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\EntityRepository,
    Doctrine\ORM\NoResultException,
    PoradnikPiwny\Entities\BeerRanking;

class BeerRankingsRepository extends EntityRepository
{ 
    /**
     * @param int $value
     * @param PoradnikPiwny\Entites\User $user
     * @param PoradnikPiwny\Entites\Beer $beer
     */
    public function addBeerRanking($value, $beer, $user)
    {       
        $beerRanking = new BeerRanking($value,$beer,$user);
        
        $this->_em->persist($beerRanking);
        $this->_em->flush();
    }
    
    /**
     * @param \PoradnikPiwny\Entities\BeerRanking $beerRanking
     * @param int $value
     */
    public function editBeerRanking($beerRanking, $value)
    {       
        $beerRanking->setValue($value);
        
        $this->_em->persist($beerRanking);
        $this->_em->flush();
    }
    
   /**
    * @param \PoradnikPiwny\Entities\Beer $beer
    * @param \PoradnikPiwny\Entities\User $user
    * @param float $rank
    */
    public function updateRankingAndAvg($beer,$user,$rank)
    {
        $this->_em->getConnection()->beginTransaction(); // suspend auto-commit
        try {
            $counter = $beer->getRankingCounter();
            $total = $beer->getRankingTotal();
            $beerRanking = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerRanking')
                                     ->findOneBy(array('user'=>$user,'beer'=>$beer));

            if($beerRanking)
            {
                $prevRank = $beerRanking->getValue();
                $beerRanking->setValue($rank);
                $total = $total + $rank - $prevRank;
            }
            else
            {
                $this->_em->getRepository('\PoradnikPiwny\Entities\BeerRanking')
                          ->addBeerRanking($rank,$beer,$user);

                $total = $total + $rank;
                $counter++;
            }
            // update rankingTotal,rankingCounter and rankingAvg

            $beer->setRankingTotal($total);
            $beer->setRankingCounter($counter);
            if($counter>0)
            {
                $beer->setRankingAvg($total/$counter);
            }

            $this->_em->merge($beer);
            $this->_em->flush();
            $this->_em->getConnection()->commit();
        } catch (Exception $e) {
            $this->_em->getConnection()->rollback();
            $this->_em->close();
            throw $e;
        }
    }
    
    public function getAdminRankingsByBeerId($beerId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('br', 'u')
            ->from('\PoradnikPiwny\Entities\BeerRanking', 'br')
            ->leftJoin('br.user','u')
            ->where('br.beer =:beer')
            ->setParameters(array('beer'=> intval($beerId)));
        
        /** @todo Pobieranie rankingów tylko administratorów */
        
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Pobranie rankingu na podstawie identyfikatora piwa oraz użytkownika
     * 
     * @param int $beerId
     * @param int $userId
     */
    public function getRankingByBeerIdAndUserId($beerId, $userId)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('br')
           ->from('\PoradnikPiwny\Entities\BeerRanking', 'br')
           ->leftJoin('br.beer', 'b')
           ->where('b.id = ?1')     
           ->leftJoin('br.user', 'u')
           ->andWhere('u.id = ?2')
           ->setParameters(array(
                1 => intval($beerId),
                2 => intval($userId)
           ));
        try {
            $result = $qb->getQuery()->getSingleResult();
            
            return $result->getValue();
        } catch(NoResultException $exc) {
            return 0;
        }
    }
}