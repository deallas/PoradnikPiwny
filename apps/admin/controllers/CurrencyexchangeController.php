<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\CurrencyExchange\Edit as EditForm,
    PoradnikPiwny\Exception\NullPointerException,
    PoradnikPiwny\Exception\CurrencyNotFoundException,
    PoradnikPiwny\Exception\CurrencyExchangeNotFoundException;

class CurrencyexchangeController extends AdminAction
{   
    /**
     * @var int
     */
    protected $_currencyId = null;
    
    /**
     * @var \PoradnikPiwny\Entities\Currency
     */
    protected $_currency = null;
    
    /**
     * @var int
     */
    protected $_currencyExchangeId = null;
    
    /**
     * @var \PoradnikPiwny\Entities\CurrencyExchange
     */
    protected $_currencyExchange = null;
    
    public function preDispatch() {
        parent::preDispatch();

        $actionName = $this->getRequest()->getActionName();
        if($actionName == 'index') {     
            $this->_currency = $this->_checkCurrency($this->_currencyId);
            $this->_currencyId = $this->_currency->getId();
        } else {
            $this->_currencyExchangeId = $this->_getParam('id', null); 
            $this->_currencyExchange = $this->_checkCurrencyExchange($this->_currencyExchangeId);
            $this->_currency = $this->_currencyExchange->getCurrencyExchanged();
            $this->_currencyId = $this->_currency->getId();
        }
        
        $menu = $this->_navigation->findById('admin_currencyexchange_index');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $this->_currencyId));
    }
    
    public function indexAction()
    {
        $offset = $this->_getParam('page', 1);
        
        $orders = array(
            'name' => 'c2.name',
            'multiplier' => 'cex.multiplier',
            'lastUpdated' => 'cex.lastUpdated'
        );
        $default_order = $db_order = 'name';
        $order = $this->_getParam('order', $default_order);
        if(isset($orders[$order])) $db_order = $orders[$order];
        
        $items = $this->_getParam('items', 10);
        $desc = (bool)$this->_getParam('desc', true);
        
        $paginator = $this->_em->getRepository('\PoradnikPiwny\Entities\Currency')
                               ->getCurrencyExchangesPaginator($this->_currency,$offset,
                                                          $items, $db_order, $desc);
        
        $this->view->items = $items;
        $this->view->order = $order;
        $this->view->desc = $desc;
        $this->view->exchanges = $paginator;
        $this->view->currency = $this->_currency;
    }
    
    public function editAction()
    {
        $menu = $this->_navigation->findById('admin_currencyexchange_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $this->_currencyExchangeId));

        $form = new EditForm(array(
            'id' => $this->_currencyExchangeId,
            'idCur' => $this->_currencyId
        ));
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {
                $this->_em->getRepository('\PoradnikPiwny\Entities\Currency')
                          ->editCurrencyExchange($this->_currencyExchange, 
                                    $form->getValue('multiplier'));
                
                $this->_helper->FlashMessenger(array('success' => 'Mnożnik został zmodyfikowany'));              
                $this->_redirect('/currencyexchange/index/id/' . $this->_currencyId);
                return;
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
            \WS\Tool::decodeFilters($form, true);
            $form->populateByCurrencyExchange($this->_currencyExchange);
        }

        $this->view->form = $form;
        $this->view->currency = $this->_currency;
        $this->view->currencyExchanged = $this->_currencyExchange->getCurrency();
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @return \PoradnikPiwny\Entities\Currency
     */
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
    
    /**
     * @return \PoradnikPiwny\Entities\CurrencyExchange
     */
    protected function _checkCurrencyExchange() 
    {
        $id = $this->_getParam('id', null);
        try {
            $cur = $this->_em->getRepository('\PoradnikPiwny\Entities\Currency')
                             ->getCurrencyExchangeById($id);
        } catch(NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora przelicznika waluty'));              
            $this->_redirect('/currency');            
        } catch(CurrencyExchangeNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dana przelicznik waluty nie istnieje'));
            $this->_redirect('/currencyexchange/index/id/' . $id);            
        }
        
        return $cur;        
    }
}