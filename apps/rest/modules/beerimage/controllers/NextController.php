<?php

use PoradnikPiwny\Controller\Action\RestAction;

class Beerimage_NextController extends RestAction
{   
    public function getAction()
    {
        $biId = $this->_getParam('beerimage_id', null);
        try {
            $bi = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                            ->getNextImageById($biId);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->view->msg = self::NULL_POINTER;
            $this->_response->badRequest();
            return;
        } catch(\PoradnikPiwny\Exception\BeerImageNotFoundException $exc) {
            $this->view->msg = self::BEER_IMAGE_NOT_FOUND;
            $this->_response->notFound();
            return;
        } catch(\PoradnikPiwny\Exception\BeerImageNeightborNotFoundException $exc) {
            $this->view->msg = self::BEER_IMAGE_NEIGHTBOR_NOT_FOUND;
            $this->_response->notFound();
            return;            
        }
        
        $this->view->beerimage = $bi->toArray(array(
                'id',
                'title',
                'path' => $this->_getImageFilter(),
                'dateAdded' => $this->_getDateFilter()
            ));
        
        $this->_response->ok();
    }
}