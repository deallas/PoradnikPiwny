<?php

namespace PoradnikPiwny\Controller\Action;

use WS\Tool,
    WS\View\Helper\Thumb as ThumbHelper;

abstract class RestAction extends \WS\Controller\Action\RestAction
{
    const NULL_POINTER = 'null_pointer';
    const INVALID_FORM_VALUES = 'invalid_form_values';
    const BEER_NOT_FOUND = 'beer_not_found';
    const BEER_IMAGE_NOT_FOUND = 'beer_image_not_found';
    const BEER_IMAGE_NEIGHTBOR_NOT_FOUND = 'beer_image_neightbor_not_found';
    const BEER_SEARCH_NOT_FOUND = 'beer_search_not_found';
    const BEER_SEARCH_RESULT_NOT_FOUND = 'beer_search_result_not_found';
    const BEER_FAMILY_NOT_FOUND = 'beer_family_not_found';
    const BEER_DISTRIBUTOR_NOT_FOUND = 'beer_distributor_not_found';
    const BEER_MANUFACTURER_NOT_FOUND = 'beer_manufacturer_not_found';
    const COUNTRY_NOT_FOUND = 'country_not_found';
    const REGION_NOT_FOUND = 'region_not_found';
    const CITY_NOT_FOUND = 'city_not_found';
    
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
    }
    
    public function preDispatch()
    {
        if(!$this->_security->hasDefaultRole())
        {
            $this->_user = $this->_security->getUser();
            $this->_userMeta = $this->_user->getMetadataArray();
            
            $this->view->user = $this->_user;
            $this->view->userMeta = $this->_userMeta;
        }
    }
    
    /*========================================================================*/
    
    public function indexAction()
    {
        $this->getAction();
    }

    public function headAction()
    {
        $this->_response->notAllowed();
    }

    public function getAction()
    {
        $this->_response->notAllowed();
    }

    public function postAction()
    {
        $this->_response->notAllowed();
    }

    public function putAction()
    {
        $this->_response->notAllowed();
    }
    
    public function deleteAction()
    {
        $this->_response->notAllowed();
    }
    
    /*========================================================================*/

    protected function _getImageFilter() { 
        return function($path, $entity) { 
            $id = $entity->getId();
            $vh = new ThumbHelper();
           
            return $vh->thumb(UPLOAD_PATH .'/images/'. $path, 
                              300, 
                              300, 
                              UPLOAD_CACHE_PATH . '/images/' . $id, 
                              Tool::getStaticUrl('/upload/_cache/images/' . $id));
        };
    }
  
    protected function _getImageThumbFilter() { 
        return function($path, $entity) { 
            $id = $entity->getId();
            $vh = new ThumbHelper();
           
            return $vh->thumb(UPLOAD_PATH .'/images/'. $path, 
                              100, 
                              100, 
                              UPLOAD_CACHE_PATH . '/images/' . $id, 
                              Tool::getStaticUrl('/upload/_cache/images/' . $id));
        };
    }
    
    protected function _getDateFilter() {
        return function($date, $entity) {
            return $date->toString();
        };
    }
}