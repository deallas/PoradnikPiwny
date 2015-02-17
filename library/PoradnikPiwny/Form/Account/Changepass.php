<?php

namespace PoradnikPiwny\Form\Account;

use WS\Form,
    WS\Validate\Confirm as ConfirmValidate,
    PoradnikPiwny\Validate\Doctrine\ConfirmPassword;

class Changepass extends Form 
{	
    public function init() 
    { 	
    	$password_old = new \Zend_Form_Element_Password('old_password');
        $password_old->setLabel('Stare hasło:')
            ->setRequired(true)
            ->setAttrib('append', array('iconClass' => 'icon-key'));
        		 				 
       	$password_old->addValidator(new ConfirmPassword($this->getParam('user_id')), true);
	 
        $password = new \Zend_Form_Element_Password('password');
        $password->setLabel('Nowe hasło:')
            ->setRequired(true)
            ->setAttrib('append', array('iconClass' => 'icon-key'));
         		 
        $password->addValidator(new \Zend_Validate_StringLength(5), true);
        	 
        $password_re = new \Zend_Form_Element_Password('re_password');
        $password_re->setLabel('Powtórz hasło:')
            ->setRequired(true)
            ->setAttrib('append', array('iconClass' => 'icon-key'));
        $password_re->addValidator(new \Zend_Validate_StringLength(5), true);
       	$password_re->addValidator(new ConfirmValidate($password), true);
	
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '"; return false;' );
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit');
        $submit->setLabel('Zmień hasło')
               ->setAttrib('class', 'btn btn-primary');
        
        $this->addElements(array(
            $password_old,
            $password,
            $password_re,
            $cancel,
            $submit
        ));
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');        
        
        $this->setAction('/account/changepass')
            ->setMethod('post');
    }
}