<?php

namespace PoradnikPiwny\Controller\Action;

use PoradnikPiwny\Security\Exception as SecurityException,
    PoradnikPiwny\Security,
    WS\Tool;

abstract class AbstractAction extends \Zend_Controller_Action
{  
    /**
     * @var bool
     */
    //protected $is_enable_js = null; //@todo wykrywanie dostępnego JS w przeglądarce

    /**
     * @var \PoradnikPiwny\Security
     */
    protected $_security;

    /**
     * @var \Bisna\Doctrine\Container $dc
     */
    protected $_dc;
    
    /**
     * @var \Doctrine\ORM\EntityManager $em
     */
    protected $_em;
    

    /**
     * @var \Zend_Translate 
     */
    protected $_translate;
    
    /**
     * @var array
     */
    protected $_options;

    /**
    * @var \Zend_Application_Bootstrap_Bootstrap
    */
    protected $_bootstrap;

    /**
     * @var \Zend_Application_Module_Bootstrap
     */
    protected $_moduleBootstrap = null;
 
    /**
     * @var \PoradnikPiwny\Entities\User
     */
    protected $_user = null;
    
    /**
     * @var array
     */
    protected $_userMeta = null;
    
    /**
     * @var Zend_Controller_Action_Helper_Redirector
     */
    protected $_redirector = null;
    
    /*========================================================================*/
    
    public function init()
    {
        $this->_bootstrap = $this->getInvokeArg('bootstrap');
        $this->_options = $this->_bootstrap->getOptions();
        
        $this->_security = $this->_bootstrap->getResource('security');
        $this->_dc = $this->_bootstrap->getResource('doctrine');
        $this->_em = $this->_dc->getEntityManager();
        
        if(\Zend_Registry::isRegistered('Zend_Translate'))
        {
            $this->_translate = \Zend_Registry::get('Zend_Translate');
        }
        
        if(\Zend_Registry::isRegistered('module_bootstrap'))
        {
            $this->_moduleBootstrap = \Zend_Registry::get('module_bootstrap');
        }
        
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }
    
    public function preDispatch()
    {
        $ip = Tool::getRealIp();
        $rule = $this->_em->getRepository('\PoradnikPiwny\Entities\BlockerRule')
                  ->getNotExpiredRule(APPLICATION_NAME, $ip);
        if($rule != null) {
            $msg = sprintf($this->_translate->_('Adres IP %s został zablokowany'), $ip);
            throw new SecurityException($msg, Security::BLOCKER_PERMISSION_DENIED);
        }
        
        if(APPLICATION_ENV == 'development')
            $this->view->headScript()->appendFile(Tool::getStaticUrl('/js/jquery.min.js'));
        else 
            $this->view->headScript()->appendFile('http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js');
        
        
        $this->view->headLink(array('rel' => 'icon',
            'href' => Tool::getStaticUrl('images/favicon.ico')));
        
        $this->view->headLink(array('rel' => 'apple-touch-icon', 
            'href' => Tool::getStaticUrl('images/favicon.ico')));

        $this->view->headLink(array('rel' => 'apple-touch-icon', 
            'href' => Tool::getStaticUrl('images/faviconx72.ico'), 
            'extras' => array('sizes' => '72x72')));

        $this->view->headLink(array('rel' => 'apple-touch-icon', 
            'href' => Tool::getStaticUrl('images/faviconx114.ico'), 
            'extras' => array('sizes' => '114x114')));
        
        
        if($this->_security != null)
        {
            if(!$this->_security->hasDefaultRole())
            {
                $this->_user = $this->_security->getUser();
                $this->_userMeta = $this->_user->getMetadataArray();

                $this->view->user = $this->_user;
                $this->view->userMeta = $this->_userMeta;
            }

            $this->view->navigation()->setAcl($this->_security->getAcl());

            if($this->_user == null) {
                $roleId = Security::ROLE_GUEST;
            } else {
                $roleId = $this->_user->getRole()->getId();
            }

            $this->view->navigation()->setRole((string)$roleId);
        }
    }
    
    /* ---------------------------------------------------------------------- */
    
    protected function _checkAjaxConnection()
    {
       if(!$this->_request->isXmlHttpRequest())
       {
           throw new SecurityException();
       }
       
       $this->_helper->_layout->disableLayout(true);
       $this->_helper->viewRenderer->setNoRender(true);
    }
    
    /** 
     * @param string $url
     * @param array $options
     */
    protected function _redirect301($url, array $options = array())
    {
        $this->_redirector->setCode(301)
                          ->setExit(true)
                          ->gotoUrl($url, $options);
    }
}