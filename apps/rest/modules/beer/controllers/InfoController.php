<?php

use PoradnikPiwny\Controller\Action\RestAction;

class Beer_InfoController extends RestAction
{   
    public function getAction()
    {
        $beerId = $this->_getParam('beer_id', null);
        try {
            /* @var $beer \PoradnikPiwny\Entities\Beer */
            $beer = $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')
                              ->getBeerById($beerId, false, true);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->view->msg = self::NULL_POINTER;
            $this->_response->badRequest();
            return;
        } catch(\PoradnikPiwny\Exception\BeerNotFoundException $exc) {
            $this->view->msg = self::BEER_NOT_FOUND;
            $this->_response->notFound();
            return;
        }

        $this->view->beer = $beer->toArray(array(
                'id', 
                'name', 
                'alcohol',
                'extract',
                'malt',
                'type',
                'filtered',
                'pasteurized',
                'flavored',
                'placeofbrew',
                'rankingAvg', 
                'rankingWeightedAvg', 
                'dateAdded' => $this->_getDateFilter(),
                'family' => array(
                    'id',
                    'name'                    
                ),
                'distributor' => array(
                    'id',
                    'name'
                ),
                'manufacturer' => array(
                    'id',
                    'name'
                ),
                'country' => array(
                    'id',
                    'name'
                ),
                'region' => array(
                    'id',
                    'name'
                ),
                'city' => array(
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
        
        $this->_response->ok();
    }
}