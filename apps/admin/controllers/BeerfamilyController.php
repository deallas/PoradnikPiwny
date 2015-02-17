<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\BeerFamily\Add as AddForm;

class BeerfamilyController extends AdminAction
{
    public function indexAction()
    {
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily');
        $options = $this->_setupOptionsPaginator($rep); 
        $paginator = $rep->getPaginator($options);
        
        $this->view->family = $paginator;
    }
    
    public function addAction()
    {    
        $allParents = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily')
                                ->getSortedParents();
        $form = new AddForm(array(
            'parents' => $allParents,
            'request' => $_POST
        ));
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {   
                $parents = $this->_getParentsByIds($form->getParentIds(), $allParents);
                $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily')
                          ->addChild($form->getValue('name'), $parents);
                
                $this->_helper->FlashMessenger(array('info' => 'Potomek został dodany'));              
                $this->_redirect('/beerfamily');          
            } else {
                \WS\Tool::decodeFilters($form);
            }
        }

        $this->view->form = $form;        
    }
    
    public function editAction()
    {
        $id = $this->_getParam('id', null);  
        $beerfamily = $this->_checkBeerFamily($id);
        
        $menu = $this->_navigation->findById('admin_beerfamily_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));
        
        $allParents = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily')
                                ->getSortedParents($beerfamily);
        
        $isPost = $this->getRequest()->isPost();
        
        $form = new AddForm(array(
            'parents' => $allParents,
            'request' => $_POST,
            'isEdit' => true,
            'id' => $id,
            'beerfamily' => ($isPost) ? null : $beerfamily
        ));
        if($isPost)
        {
            if($form->isValid($_POST)) { 
                $parents = $this->_getParentsByIds($form->getParentIds(), $allParents);
                
                $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily')
                          ->editChild($beerfamily, $form->getValue('name'), $parents);
                
                $this->_helper->FlashMessenger(array('info' => 'Potomek został zedytowany'));              
                $this->_redirect('/beerfamily');            
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
            \WS\Tool::decodeFilters($form, true);
        }
        
        $this->view->form = $form;
    }
    
    public function deleteAction()
    {
        $id = $this->_getParam('id', null);  
        $beerfamily = $this->_checkBeerFamily($id);
        
        $this->_em->remove($beerfamily);
        $this->_em->flush();

        $this->_helper->FlashMessenger(array('success' => 'Potomek został usunięty'));              
        $this->_redirect('/beerfamily');
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\BeerFamily
     */
    protected function _checkBeerFamily($id)
    {  
        try {
            $beerfamily = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerFamily')
                                    ->getFamilyById($id);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora potomka'));              
            $this->_redirect('/beerfamily');            
        } catch(\PoradnikPiwny\Exception\BeerFamilyNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dany potomek nie istnieje'));
            $this->_redirect('/beerfamily');            
        }
        
        return $beerfamily;
    }
    
    /**
     * @param type $ids
     * @param type $allParents
     */
    protected function _getParentsByIds($ids, $allParents)
    {
        $parents = array();
        foreach($ids as $id)
        {
            $parents[$allParents[$id]->getId()] = $allParents[$id];
        }
        
        return $parents;
    }
}