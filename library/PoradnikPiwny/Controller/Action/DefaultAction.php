<?php

namespace PoradnikPiwny\Controller\Action;

use PoradnikPiwny\Controller\Action\AbstractAction,
    WS\Tool;

abstract class DefaultAction extends AbstractAction
{
    public function preDispatch() 
    {
        parent::preDispatch();
        
        $this->view->headTitle('Poradnik Piwny')->setSeparator(' | ');
        $this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $this->view->doctype('XHTML1_STRICT');
        $this->view->setEncoding('UTF-8'); 
        
        $this->view->headLink()->appendStylesheet(Tool::getStaticUrl('/css/_default/style.css.php'));
    }
}
