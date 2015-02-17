<?php

use PoradnikPiwny\Controller\Action\RestAction;

class Beer_ListController extends RestAction
{   
    public function getAction()
    {
        /* Numer strony */
        $page = $this->_getParam('page', 1);

        /* Ilość elementów na stronie */
        $d_items = 10;
        $items = $this->_getParam('items', $d_items);

        if(!in_array($items, array($d_items,20,30)))
        {
            $items = $d_items;
        }
        
        /* Sortowanie wg */
        $orders = array(
            'beer_name' => 'b.name',
            'distributor_name' => 'bd.name',
            'manufacturer_name' => 'bm.name',
            'beer_avg' => 'b.rankingAvg',
            'beer_dateAdded' => 'b.dateAdded'
        );
        $defaultOrder = 'beer_name'; 
        $desc = (bool)$this->_getParam('desc', false);
        $order = $this->_getParam('order', $defaultOrder);
        if(isset($orders[$order])) $dbOrder = $orders[$order];

        $paginator = $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')
                               ->getBeersPaginator($page, $items, $dbOrder,
                                                   $desc, $this->_user, true);
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
        $this->view->currentPageNumber = $paginator->getCurrentPageNumber();
        $this->view->itemCountPerPage = $paginator->getItemCountPerPage();
        $this->view->totalItemCount = $paginator->getTotalItemCount();
            
        $this->_response->ok();
    }
}