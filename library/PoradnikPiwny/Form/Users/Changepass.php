<?php

namespace PoradnikPiwny\Form\Users;

class Changepass extends \WS\Form 
{	
    public function init() 
    {
    	$id = $this->getParam('id_user');

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
       	$password_re->addValidator(new \WS\Validate\Confirm($password), true);
	
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/users"; return false;' );
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit');
        $submit->setLabel('Zmień hasło')
               ->setAttrib('class', 'btn btn-primary');
        
        $this->addElements(array(
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
        
        $this->setAction('/users/changepass/id/' . $id)
            ->setMethod('post');
    }
}