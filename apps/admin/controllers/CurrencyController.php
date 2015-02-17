<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\Currency\Add as AddForm,
    PoradnikPiwny\Exception\NullPointerException,
    PoradnikPiwny\Exception\CurrencyNotFoundException;

class CurrencyController extends AdminAction
{   
    public function indexAction()
    {
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\Currency');
        $options = $this->_setupOptionsPaginator($rep); 
        $paginator = $rep->getPaginator($options);
        
        $this->view->currencies = $paginator;
    }
    
    public function addAction()
    {
        $form = new AddForm();
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {   
                $this->_em->getRepository('\PoradnikPiwny\Entities\Currency')
                          ->addCurrency($form->getValue('name'), $form->getValue('symbol'));
                
                $this->_helper->FlashMessenger(array('info' => 'Dodano nową walutę'));              
                $this->_redirect('/currency');
            } else {
                \WS\Tool::decodeFilters($form);
            }
        }

        $this->view->form = $form;
    }
    
    public function editAction()
    {
        $cur = $this->_checkCurrency();

        $menu = $this->_navigation->findById('admin_currency_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $cur->getId()));

        $form = new AddForm(array(
            'isEdit' => true,
            'id' => $cur->getId()
        ));
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {
                $this->_em->getRepository('\PoradnikPiwny\Entities\Currency')
                          ->editCurrency($cur, 
                                    $form->getValue('name'),
                                    $form->getValue('symbol'));
                
                $this->_helper->FlashMessenger(array('success' => 'Waluta została zmodyfikowana'));              
                $this->_redirect('/currency');
                return;
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
            \WS\Tool::decodeFilters($form, true);
            $form->populateByCurrency($cur);
        }

        $this->view->form = $form;
    }
    
    public function deleteAction()
    {
        $cur = $this->_checkCurrency();
        $this->_em->getRepository('\PoradnikPiwny\Entities\Currency')
                  ->removeCurrency($cur);
        
        $this->_helper->FlashMessenger(array('success' => 'Waluta została usunięta'));              
        $this->_redirect('/currency');
    }
    
    /* ---------------------------------------------------------------------- */
    
    protected function _checkCurrency()
    {
        $id = $this->_getParam('id', null);
        try {
            $cur = $this->_em->getRepository('\PoradnikPiwny\Entities\Currency')
                             ->getCurrencyById($id);
        } catch(NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora waluty'));              
            $this->_redirect('/currency');            
        } catch(CurrencyNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dana waluta nie istnieje'));
            $this->_redirect('/currency');            
        }
        
        return $cur;
    }
}