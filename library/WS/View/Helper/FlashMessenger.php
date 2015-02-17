<?php

namespace WS\View\Helper;

class FlashMessenger extends \Zend_View_Helper_Abstract
{
    /**
     * @var \Zend_Controller_Action_Helper_FlashMessenger
     */
    private $_flashMessenger = null;

    /**
     * Display Flash Messages.
     *
     * @param  string $key Message level for string messages
     * @param  string $template Format string for message output
     * @return string Flash messages formatted for output
     */
    public function flashMessenger($key = 'warning',
                                   $template='<p>%s</p>')
    {
        $flashMessenger = $this->_getFlashMessenger();

        //get messages from previous requests
        $messages = $flashMessenger->getMessages();

        //add any messages from this request
        if ($flashMessenger->hasCurrentMessages()) {
            $messages = array_merge(
                $messages,
                $flashMessenger->getCurrentMessages()
            );
            //we don't need to display them twice.
            $flashMessenger->clearCurrentMessages();
        }

        $h = new \Zend_View_Helper_Translate();
        
        //initialise return string
        $output ='';

        //process messages
        
        $msg = array(
            'success' => array(),
            'warning' => array(),
            'error' => array(),
            'info' => array()
        );
        
        foreach ($messages as $message)
        {
            if (is_array($message)) {
                list($key,$message) = each($message);
            }
            if(!isset($msg[$key])) $key = 'warning';
            $msg[$key][] = sprintf($template,$message);
        }
        
        if(!empty($msg['error'])) {
            $output .= '<div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4>' . $h->translate('Błąd') . '</h4>
                ' . implode('', $msg['error']) . '
            </div>';
        }
        
        if(!empty($msg['warning'])) {
            $output .= '<div class="alert alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4>' . $h->translate('Ostrzeżenie') . '</h4>
                ' . implode('', $msg['warning']) . '
            </div>';
        }

        if(!empty($msg['info'])) {
            $output .= '<div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4>' . $h->translate('Powiadomienie') . '</h4>
                ' . implode('', $msg['info']) . '
            </div>';
        }
        
        if(!empty($msg['success'])) {
            $output .= '<div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                ' . implode('', $msg['success']) . '
            </div>';
        }

        return $output;
    }

    /**
     * Lazily fetches FlashMessenger Instance.
     *
     * @return \Zend_Controller_Action_Helper_FlashMessenger
     */
    public function _getFlashMessenger()
    {
        if (null === $this->_flashMessenger) {
            $this->_flashMessenger =
                \Zend_Controller_Action_HelperBroker::getStaticHelper(
                    'FlashMessenger');
        }
        return $this->_flashMessenger;
    }
}