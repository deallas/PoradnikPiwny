<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\BeerDistributor\Add as AddForm;

class BeerdistributorController extends AdminAction
{   
    public function indexAction()
    {
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerDistributor');
        $options = $this->_setupOptionsPaginator($rep); 
        $paginator = $rep->getPaginator($options);
        
        $this->view->distributors = $paginator;
    }

    public function addAction()
    {
        $form = new AddForm();
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {   
                $this->_em->getRepository('\PoradnikPiwny\Entities\BeerDistributor')
                          ->addDistributor($form->getValue('name'));
                
                $this->_helper->FlashMessenger(array('info' => 'Dystrybutor został dodany'));              
                $this->_redirect('/beerdistributor');
            } else {
                \WS\Tool::decodeFilters($form);
            }
        }

        $this->view->form = $form;
    }

    public function editAction()
    {
        $id = $this->_getParam('id', null);
        $distributor = $this->_checkDistributor($id);

        $menu = $this->_navigation->findById('admin_beerdistributor_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id)); 
        
        $form = new AddForm(array(
            'id' => $id,
            'isEdit' => true
        ));
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {
                $this->_em->getRepository('\PoradnikPiwny\Entities\BeerDistributor')
                          ->editDistributor($distributor, $form->getValue('name'));
                
                $this->_helper->FlashMessenger(array('success' => 'Dystrybutor został zedytowany'));              
                $this->_redirect('/beerdistributor');
                return;
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
            \WS\Tool::decodeFilters($form, true);
            $form->populateByDistributor($distributor);
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $id = $this->_getParam('id', null);
        $distributor = $this->_checkDistributor($id);

        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerDistributor')
                  ->removeDistributor($distributor);

        $this->_helper->FlashMessenger(array('success' => 'Dystrybutor został usunięty'));              
        $this->_redirect('/beerdistributor');
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\BeerDistributor
     */
    protected function _checkDistributor($id)
    {    
        try {
            $distributor = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerDistributor')
                                     ->getDistributorById($id);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora dystrybutora'));              
            $this->_redirect('/beerdistributor');            
        } catch(\PoradnikPiwny\Exception\DistributorNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dany dystrybutor nie istnieje'));
            $this->_redirect('/beerdistributor');
        }
        
        return $distributor;
    }
}