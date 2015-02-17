<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\EntityRepository,
    PoradnikPiwny\Entities\Beer;

class BeerPricesRepository extends EntityRepository
{   
    /**
     * Zwraca listę cen dla danego identyfikatora piwa
     * 
     * @param int $id
     * @return array
     */
    public function getPricesByBeerId($id)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(array('bp', 'c'))
            ->from('\PoradnikPiwny\Entities\BeerPrice', 'bp')
            ->leftJoin('bp.beer', 'b')
            ->leftJoin('bp.currency', 'c')
            ->where('b.id=' . intval($id));
 
        return $qb->getQuery()->getResult();
    }
    
    /**
     * Usuwa wszystkie ceny dla danego piwa
     * 
     * @param \PoradnikPiwny\Entities\Beer $beer
     */
    public function removeAllPricesForBeer(Beer $beer)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->delete('\PoradnikPiwny\Entities\BeerPrice', 'bp')
           ->where('bp.beer = :beer')
           ->setParameter('beer', $beer);
           
        $qb->getQuery()->execute();
    }
}
