<?php

namespace WS\Application\Resource;

class Html extends \Zend_Application_Resource_ResourceAbstract
{	
    public function init()
    {
        $this->getBootstrap()->bootstrap('layout');
        $layout = $this->getBootstrap()->getResource('layout');
        $view = $layout->getView();

        $options = $this->getOptions();

        $view->headTitle($options['head']['title']['value'])->setSeparator($options['head']['title']['separator']);
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=' . $options['encoding']);
        $view->headMeta()->setName('keywords', $options['head']['keywords']);
        $view->headMeta()->setName('description', $options['head']['description']); 
        $view->headLink()->setStylesheet(array('rel' => 'SHORTCUT ICON', 'href' => APPLICATION_URL . '/' . $options['head']['icon']));
        $view->doctype($options['doctype']);
        $view->setEncoding($options['encoding']);  
    }
}