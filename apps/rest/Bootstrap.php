<?php

use WS\Controller\Request as Request,
    WS\Controller\Response as Response,
    WS\Controller\Plugin\RestHandler as RestHandlerPlugin,
    WS\Controller\Action\Helper\ContextSwitch as ContextSwitchHelper,
    WS\Controller\Action\Helper\RestContexts as RestContextHelper;

class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
    public function _initREST()
    {
        $frontController = \Zend_Controller_Front::getInstance();

        $frontController->setRequest(new Request());
        $frontController->setResponse(new Response());

        $restRoute = new \Zend_Rest_Route($frontController);
        $frontController->getRouter()->addRoute('default', $restRoute);
        
        $frontController->registerPlugin(new RestHandlerPlugin($frontController));
        
        $contextSwitch = new ContextSwitchHelper();
        \Zend_Controller_Action_HelperBroker::addHelper($contextSwitch);

        $restContexts = new RestContextHelper();
        \Zend_Controller_Action_HelperBroker::addHelper($restContexts);
    }
}