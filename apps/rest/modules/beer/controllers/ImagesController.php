<?php

use PoradnikPiwny\Controller\Action\RestAction;

class Beer_ImagesController extends RestAction
{   
    public function getAction()
    {
        $beerId = $this->_getParam('beer_id', null);
        try {
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
        
        /* Numer strony */
        $page = $this->_getParam('page', 1);

        /* Ilość elementów na stronie */
        $d_items = 10;
        $items = $this->_getParam('items', $d_items);

        if(!in_array($items, array($d_items,20,30)))
        {
            $items = $d_items;
        }
        
        $paginator = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                               ->getPaginator($beer, $page, $items);
        $data = array();
        
        foreach($paginator as $image)
        {
            $data[] = $image->toArray(array(
                'id',
                'title',
                'path' => $this->_getImageThumbFilter(),
                'position',
                'dateAdded' => $this->_getDateFilter()
            ));
        }

        $this->view->images = $data;
        $this->view->currentPageNumber = $paginator->getCurrentPageNumber();
        $this->view->itemCountPerPage = $paginator->getItemCountPerPage();
        $this->view->totalItemCount = $paginator->getTotalItemCount();
            
        $this->_response->ok();
    }

    /* ---------------------------------------------------------------------- */
}