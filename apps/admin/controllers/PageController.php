<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\Page\Edit as EditForm,
    WS\Config\Writer\IniPhp as WriterIniPhp;

class PageController extends AdminAction
{
    protected $_config;
    
    public function preDispatch() {
        parent::preDispatch();
    
        $this->_config = new Zend_Config_Ini(APPS_CONFIG_PATH . '/html.ini.php');
        $this->_config = $this->_config->toArray();
    }
    
    public function indexAction()
    {
        $this->view->settings = $this->_config;
    }

    public function editAction() 
    {
        $form = new EditForm();
        if ($this->getRequest()->isPost()) 
        {
            if ($form->isValid($this->getRequest()->getPost())) 
            {
                $this->_config = array(
                    'head' => array(
                        'title' => array(
                            'value' => $form->getValue('title'),
                            'separator' => $form->getValue('separator')
                        ),
                        'keywords' => $form->getValue('keywords'),
                        'description' => $form->getValue('description'),
                        'icon' => $this->_config['head']['icon']
                    ),
                    'encoding' => 'UTF-8',
                    'doctype' => 'HTML5'
                );

                $writer = new WriterIniPhp(
                    array(
                        'config'   => new \Zend_Config($this->_config),
                        'filename' => APPS_CONFIG_PATH . '/html.ini.php'
                    )
                );
                $writer->write();
                
                $this->_helper->FlashMessenger(array('success' => 'Dane strony zostały zaktualizowane'));              
                $this->_redirect('/page');
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
            \WS\Tool::decodeFilters($form, true);
            $form->populate($this->_config);	
            $form->title->setValue($this->_config['head']['title']['value']);	
            $form->keywords->setValue($this->_config['head']['keywords']);	
            $form->description->setValue($this->_config['head']['description']);	
            $form->separator->setValue($this->_config['head']['title']['separator']);
        }
        $this->view->form = $form;		
    }
}