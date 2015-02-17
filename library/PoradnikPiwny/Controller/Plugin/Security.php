<?php

namespace PoradnikPiwny\Controller\Plugin;

use PoradnikPiwny\Security as PPSecurity,
    PoradnikPiwny\Security\Exception as SecurityException;

class Security extends \Zend_Controller_Plugin_Abstract 
{ 
    /**
     * @var \PoradnikPiwny\Security
     */
    protected $security;
	
    public function preDispatch(\Zend_Controller_Request_Abstract $request) 
    {     
        $fc = \Zend_Controller_Front::getInstance();
        if(!$fc->getDispatcher()->isDispatchable($request)) {
            return;
        }
        $this->security = PPSecurity::getInstance();
    	try {
            $this->security->init();
    	} catch(SecurityException $exception) {
    	    if($this->security->hasErrorPage()) {
                $this->redirect($this->security->getErrorPage(), $exception);
                return;
            } else {
                throw new SecurityException($exception->getMessage(), $exception->getCode());
            }
    	}
        
        $resource = '';
        
        if(APPLICATION_NAME != 'default')
        {
            $resource .= APPLICATION_NAME;
        }
        $resource .= '_';
        
        $moduleName = $request->getModuleName();
        if($moduleName != 'default')
        {
            $resource .= $moduleName;
        }
        $resource .= '_';
        
    	$resource .= $request->getControllerName();
    	$privilege = $request->getActionName();
        
    	$code = $this->security->isAllowed($resource, $privilege);
        
    	switch($code) {
            case PPSecurity::ACL_PERMISSION_DENIED:
                if(!$this->security->hasDefaultRole()) {
                    throw new SecurityException(null, PPSecurity::ACL_PERMISSION_DENIED);
                }

                if($errorPage = $this->security->getErrorPage()) {
                    $this->redirect($errorPage, new SecurityException(null, PPSecurity::ACL_PERMISSION_DENIED));
                } else {
                    throw new SecurityException(null, PPSecurity::ACL_PERMISSION_DENIED);
                }
                break;
            case PPSecurity::ACL_RESOURCE_NOT_FOUND:
                throw new \Zend_Controller_Action_Exception('Resource "' . $resource . '" is not found');
                break;
    	}
    }
    
    private function redirect(array $errorPage, \Exception $errorHandler)
    {
        if($errorPage == null)
        {
            throw $errorHandler;
        }
        
    	$this->_request->setModuleName($errorPage['module']);
	$this->_request->setControllerName($errorPage['controller']);
        $this->_request->setActionName($errorPage['action']);
        $this->_request->setParam('exception', $errorHandler);
    }
}