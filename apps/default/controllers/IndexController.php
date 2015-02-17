<?php

use PoradnikPiwny\Controller\Action\DefaultAction;

class IndexController extends DefaultAction
{   
    public function indexAction()
    {
        $this->view->beers = $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')
                                       ->getLastAddedBeers(20, $this->_user, true);
    }
    
    public function loginAction()
    {
        
    }
    
    /*public function beerAction()
    {
        $id = $this->getParam('id', null);
        if($id == null)
        {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora piwa'));              
            $this->_redirect('/');
            return;
        }

        $beer = $this->dc->getEntityManager()->find('\PoradnikPiwny\Entities\Beer', $id);
        
        if(!$beer)
        {
            $this->_helper->FlashMessenger(array('warning' => 'Dane piwo nie istnieje'));
            $this->_redirect('/');
            return;
        }
        
        $this->view->beer = $beer;
    }*/
}

