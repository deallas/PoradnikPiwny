<?php

namespace PoradnikPiwny\Application\Resource;

use PoradnikPiwny\Security as PPSecurity,
    PoradnikPiwny\Security\Exception as SecurityException,
    PoradnikPiwny\Exception as PPException;

class Security extends \Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {
        $this->getBootstrap()->bootstrap('doctrine');
        
        $options = $this->getOptions();					
        $security = new PPSecurity($options);
        PPSecurity::setInstance($security);
        
        if(isset($options['plugin']))
        {
            $plugin = null;
            if(isset($options['plugin']['filename']))
            {
                if(\Zend_Loader::isReadable($options['plugin']['filename'])) {
                    require_once $options['plugin']['filename'];
                } else {
                    throw new SecurityException('File "' . $options['plugin']['filename'] . '" is not readable');
                }
            }

            if(class_exists($options['plugin']['class'])) {
                $plugin = new $options['plugin']['class'];
            } else {
                throw new SecurityException('Class "' . $options['plugin']['class'] . '" does not exists');
            }

            if(!($plugin instanceof \Zend_Controller_Plugin_Abstract))
            {
                throw new PPException('Class "' . $options['plugin']['class'] . '" is not an instance of class Zend_Controller_Plugin_Abstract');
            }

            $this->getBootstrap()->bootstrap('frontController');
            $fc = $this->getBootstrap()->getResource('frontController');
            $fc->registerPlugin($plugin);	
        }

        return $security;
    }
}