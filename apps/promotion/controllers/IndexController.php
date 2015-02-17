<?php

use PoradnikPiwny\Controller\Action\AbstractAction,
    PoradnikPiwny\Entities\Newsletter;

class IndexController extends AbstractAction
{   
    const MSG_EMAIL_WRONG = 'email_wrong';
    const MSG_EMAIL_EXISTS = 'email_exists';
    const MSG_STATUTE_NOT_ACCEPT = 'statute_not_accept';
    
    const STATUS_ERROR = 0;
    const STATUS_OK = 1;
    
    public function indexAction()
    {   
    }
    
    /* -------------------------------------- */
    
    public function addnewsletterAction()
    {
        $this->_checkAjaxConnection();
        
        $statute = (bool)$this->getParam('statute', false); 
        if(!$statute)
        {
            $this->_sendMsg(self::MSG_STATUTE_NOT_ACCEPT, self::STATUS_ERROR);  
        }
        
        $email = $this->getParam('email', null);

        $v_email = new \Zend_Validate_EmailAddress();
        if(!$v_email->isValid($email))
        {
            $this->_sendMsg(self::MSG_EMAIL_WRONG, self::STATUS_ERROR);  
        }
        
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\Newsletter');
        
        if($rep->getNewsletterByEmail($email) != null)
        { 
            //$this->_sendMsg(self::MSG_EMAIL_EXISTS, self::STATUS_ERROR); 
            $this->_sendMsg();
        }
        
        $rep->addNewsletter($email, Newsletter::STATUS_AKTYWNY);
        
        $this->_sendMsg();
    }
    
    /*====================================================== */
    
    private function _sendMsg($msg = null, $status = self::STATUS_OK)
    {
        if($msg != null) {
            echo \Zend_Json::encode(array('status' => $status, 'msg' => $msg));  
        } else {
            echo \Zend_Json::encode(array('status' => $status));  
        }
        die();
    }
}