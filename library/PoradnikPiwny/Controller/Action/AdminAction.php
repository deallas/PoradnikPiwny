<?php

namespace PoradnikPiwny\Controller\Action;

use PoradnikPiwny\Controller\Action\AbstractAction,
    PoradnikPiwny\Entities\Repositories\AbstractPaginatorRepository,
    WS\Tool;

abstract class AdminAction extends AbstractAction
{   
    const DEFAULT_THEME = 'bootstrap';
    
    protected $_themes = array(
        'bootstrap' => 'Domyślny',
        'amelia' => 'Amelia',
        'cerulean' => 'Cerulean',
        'cyborg' => 'Cyborg',
        'journal' => 'Journal',
        'readable' => 'Readable',
        'simplex' => 'Simplex',
        'slate' => 'Slate',
        'spacelab' => 'Spacelab',
        'spruce' => 'Spruce',
        'superhero' => 'Superhero',
        'united' => 'United'
    );
    
    protected $_userMeta;
    
    /**
     * @var \Zend_Navigation
     */
    protected $_navigation;
    
    public function preDispatch()
    {
        parent::preDispatch();
        
        $this->_helper->_layout->setLayout('admin-panel');
        $theme = self::DEFAULT_THEME;
        
        if(!$this->_security->hasDefaultRole()) {
            if(isset($this->_userMeta['theme'])) {
                $themeName = $this->_userMeta['theme'];
                if(!isset($this->_themes[$themeName])) {
                    $theme = self::DEFAULT_THEME;            
                }
            }
        }
        
        $this->view->headTitle('PP Admin')->setSeparator(' | ');
        $this->view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $this->view->doctype('XHTML1_STRICT');
        $this->view->setEncoding('UTF-8'); 
        
        $this->view->headLink()->appendStylesheet(Tool::getStaticUrl('/css/_admin/style.css.php'));
        $this->view->headLink()->appendStylesheet(Tool::getStaticUrl('/css/_admin/bootstrap-themes/' . $theme . '/bootstrap.css.php'));
        $this->view->headLink()->appendStylesheet(Tool::getStaticUrl('/css/_admin/bootstrap-themes/' . $theme . '/admin.css'));
        $this->view->headLink()->appendStylesheet(Tool::getStaticUrl('/css/_admin/bootstrap-responsive.min.css'));
        $this->view->headLink()->appendStylesheet(Tool::getStaticUrl('/css/font-awesome.min.css'));
        $this->view->headLink()->appendStylesheet(Tool::getStaticUrl('/css/font-awesome-ie7.min.css'), 'screen', 'IE7'); 
            
        $this->view->headScript()->appendFile(Tool::getStaticUrl('/js/_admin/bootstrap.min.js'));
        
        $config = include CONFIG_PATH . '/nav.php';
        $this->_navigation = new \Zend_Navigation($config);
        $this->view->navigation($this->_navigation);
    }
    
    protected function _setupOptionsPaginator(AbstractPaginatorRepository $rep)
    {
        if($this->getRequest()->isPost()) {
            if(isset($_POST['reset'])) {
                $options = $rep->clearPaginatorOptions($this->_user);
            } else {
                $options = $rep->setPaginatorOptions($this->_user,
                                                     $this->_getParam('opt_orders', null),
                                                     $this->_getParam('opt_order', null),
                                                     $this->_getParam('opt_items', null),
                                                     $this->_getParam('opt_desc', null));                
            }
        } else {
            $options = $rep->mergePaginatorOptions(
                $rep->getPaginatorOptions($this->_user),
                array(
                    'order' => $this->_getParam('order', null),
                    'page' => $this->_getParam('page', null),
                    'items' => $this->_getParam('items', null),
                    'desc' => $this->_getParam('desc', null)
                )
            );
        }
       
        $this->view->options = $options;
        $this->view->available_orders = $rep->getAvailableOrders();

        return $options;
    }
}
