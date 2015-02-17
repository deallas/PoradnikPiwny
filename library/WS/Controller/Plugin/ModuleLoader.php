<?php

namespace WS\Controller\Plugin;

class ModuleLoader extends \Zend_Controller_Plugin_Abstract
{
    protected $modules;

    public function __construct()
    {
        $modules = \Zend_Controller_Front::getInstance()->getControllerDirectory();
        $this->modules = $modules;
    }

    public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        if(!isset($this->modules[$module])) {
            throw new \Zend_Exception('Module "' . $module . '" does not exist');
        }

        $bootstrap = \Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $application = $bootstrap->getApplication();

        $path = $this->modules[$module];
        $class = ucfirst($module) . '_Bootstrap';
        if(\Zend_Loader::loadFile('Bootstrap.php', dirname($path)) && class_exists($class)) 
        {
            $bootstrap = new $class($application);
            $bootstrap->bootstrap();

            \Zend_Registry::set('module_bootstrap', $bootstrap);
        }
    }
}