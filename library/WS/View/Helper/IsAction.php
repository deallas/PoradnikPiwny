<?php

namespace WS\View\Helper;

class IsAction extends \Zend_View_Helper_Abstract {
        
    public function isAction(array $params = array()) 
    {       
        $request = \Zend_Controller_Front::getInstance()->getRequest();
        
        if(isset($params['action']))
        {
            if($params['action'] != $request->getActionName()) return false;
        }
        if(isset($params['controller']))
        {
            if($params['controller'] != $request->getControllerName()) return false;
        }
        if(isset($params['module']))
        {
            if($params['module'] != $request->getModuleName()) return false;
        }
        
        return true;
    }

}