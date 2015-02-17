<?php

namespace PoradnikPiwny;

use WS\Controller\Action\Helper\LayoutLoader;

class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{   
    const DEFAULT_LANGUAGE = 'pl';
    
    public function _initGlobalDomainCookie()
    {
    	if (PHP_SAPI != 'cli') {
            // 31536000 = 60*60*24*365
            session_set_cookie_params(31536000, BASE_URL, '.' . DOMAIN_NAME);
            session_name('PP_SESS_ID');
        }
    }
    
    public function _initZFDebug()
    {
        if (in_array(APPLICATION_ENV, array('development'))) {
            $this->bootstrap('doctrine');
            $doctrine = $this->getResource('doctrine');
            $em = $doctrine->getEntityManager();
            $em->getConnection()->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\DebugStack());  

            $options = array(
                'plugins' => array(
                    'Html',
                    'File',
                    'ZFDebug_Controller_Plugin_Debug_Plugin_Doctrine2'  => array(
                        'entityManagers' => array($em),
                    ),
                    'Exception',
                    'Memory',
                    'Time',
                    'Variables'
                )
            );

            $debug = new \ZFDebug_Controller_Plugin_Debug($options);
            $this->bootstrap('frontController');
            $frontController = $this->getResource('frontController');
            $frontController->registerPlugin($debug);
        }
    }

    
    public function _initLocaleBrowser()
    {
        if (PHP_SAPI != 'cli') {
            $this->bootstrap('locale');
            $this->bootstrap('translate');

            /* @var $locale \Zend_Locale */
            $locale = $this->getResource('locale');
            try {
                $locale->setLocale(\Zend_Locale::BROWSER);
                $language = $locale->getLanguage();      
            } catch(Exception $exc) {
                $language = self::DEFAULT_LANGUAGE;
            }
            
            /* @var $translate \Zend_Translate */
            $translate = $this->getResource('translate');

            if(isset($_COOKIE['PP_LANG'])) {
                $language = $_COOKIE['PP_LANG'];
                $locale->setLocale($language);
            }

            if(!in_array($language, $translate->getList())) {
                $locale->setLocale(self::DEFAULT_LANGUAGE);
                $language = $locale->getLanguage(); 
            }
            
            if(isset($_COOKIE['PP_LANG']))
            {
                if($_COOKIE['PP_LANG'] != $language)
                {
                    setcookie('PP_LANG', $language, time() + 31536000, BASE_URL, '.' . DOMAIN_NAME);
                }
            } else {
                setcookie('PP_LANG', $language, time() + 31536000, BASE_URL, '.' . DOMAIN_NAME);
            }
            
            define('PP_LANG', $language);
        }
    }
    
    public function _initViewHelpers()
    {   
        $this->bootstrap('layout');
        
        /* @var $layout \Zend_Layout */
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $view->addHelperPath(LIBRARY_PATH . '/WS/View/Helper/', '\WS\View\Helper');
        $view->headScript()->prependScript('var base_url = "' . BASE_URL . '";');
        
        \Zend_Controller_Action_HelperBroker::addHelper(new LayoutLoader());
    }
}