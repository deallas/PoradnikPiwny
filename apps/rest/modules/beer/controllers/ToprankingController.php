<?php

use PoradnikPiwny\Controller\Action\RestAction;

class Beer_ToprankingController extends RestAction
{   
    public function getAction()
    {
        $items = 10;
        $paginator = $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')
                               ->getTopBeers($items, $this->_user, true);
        $data = array();
        
        foreach($paginator as $beer)
        {
            $data[] = $beer->toArray(array(
                'id', 
                'name', 
                'rankingAvg', 
                'rankingWeightedAvg', 
                'dateAdded' => $this->_getDateFilter(),
                'distributor' => array(
                    'id',
                    'name'
                ),
                'manufacturer' => array(
                    'id',
                    'name'
                ),
                'image' => array(
                    'id',
                    'title',
                    'path' => $this->_getImageThumbFilter(),
                    'dateAdded' => $this->_getDateFilter()
                )
            ));
        }
        
        $this->view->beers = $data;           
        $this->_response->ok();
    }
}