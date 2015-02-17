<?php

namespace WS\Controller\Action\Helper;

class LayoutLoader extends \Zend_Controller_Action_Helper_Abstract
{
    public function preDispatch()
    {
        $moduleName = $this->getRequest()->getModuleName();
        $options = $this->getActionController()->getInvokeArg('bootstrap')->getOptions();
        $layout = $this->getActionController()->getHelper('layout');

        if(isset($options['is_enable_js'])) {
            if($options['is_enable_js']) {
                $layout->setLayout('script/' . $moduleName);
            } else {
                $layout->setLayout('noscript/' . $moduleName);
            }
        } else {
            $layout->setLayout($moduleName);
        }
    }
}