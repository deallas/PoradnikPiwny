<?php

namespace PoradnikPiwny\Form\Users;

use WS\Validate\Doctrine\NoRecordExists,
    WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    PoradnikPiwny\Entities\User;

class Add extends \WS\Form
{
    public function init() 
    {
    	$username = new \Zend_Form_Element_Text('username');
        $username->setFilters(array('StringTrim', 'StringToLower', new HtmlspecialcharsFilter()))
                 ->setRequired(true)
                 ->setLabel('Nazwa użytkownika:')
                 ->setAttrib('append', array('iconClass' => 'icon-user'))
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(3, 50), true),
       	     	 	array(new NoRecordExists(array('class' => '\PoradnikPiwny\Entities\User', 'property' => 'username')), true),
       	     	 	array('Alnum')
       	     	 ));
        $username->getValidator('NoRecordExists')->setMessage('Nazwa użytkownika jest już zajęta', NoRecordExists::ERROR_ENTITY_EXISTS);        
        
        $email = new \Zend_Form_Element_Text('email');
        $email->setFilters(array('StringTrim'))
                 ->setLabel('Email:')
                 ->setRequired(true)
                 ->setAttrib('append', array('iconClass' => 'icon-envelope'))
                 ->setValidators(array(
                 	array(new \WS\Validate\SimpleEmailAddress(), true),
                 	array(new \Zend_Validate_StringLength(3, 50), true),
       	     	 	array(new NoRecordExists(array('class' => '\PoradnikPiwny\Entities\User', 'property' => 'email')), true),
                 ));

       	$email->getValidator('NoRecordExists')->setMessage('Inny użytkownik jest już zarejestrowany na podanym adresie email', NoRecordExists::ERROR_ENTITY_EXISTS);
        
    	$visibleName = new \Zend_Form_Element_Text('visibleName');
        $visibleName->setFilters(array('StringTrim', 'StringToLower', new HtmlspecialcharsFilter()))
                 ->setRequired(true)
                 ->setLabel('Podpis:')
                 ->setAttrib('append', array('iconClass' => 'icon-user'))
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(3, 50), true),
       	     	 	array(new NoRecordExists(array('class' => '\PoradnikPiwny\Entities\User', 'property' => 'visibleName')), true),
       	     	 	array('Alnum')
       	     	 ));
        $visibleName->getValidator('NoRecordExists')->setMessage('Nazwa użytkownika jest już zajęta', NoRecordExists::ERROR_ENTITY_EXISTS);				
        
        $name = new \Zend_Form_Element_Text('name');
        $name->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setLabel('Imię:')
                 ->setValidators(array(
                 	array('Alpha', true),
                 	array(new \Zend_Validate_StringLength(3, 50))
                 ));		
				
        $surname = new \Zend_Form_Element_Text('surname');
        $surname->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setLabel('Nazwisko:')
                 ->setValidators(array(
                 	array('Alpha', true),
                 	array(new \Zend_Validate_StringLength(3, 50), true)
                 ));
                                  	
        $password = new \Zend_Form_Element_Password('password');
        $password->setLabel('Hasło:')
        	 ->setRequired(true)
                 ->setAttrib('append', array('iconClass' => 'icon-key'))
                 ->setValidators(array(
                    array(new \Zend_Validate_StringLength(3, 50), true)
                 ));
                 		
        $password_re = new \Zend_Form_Element_Password('re_password');
        $password_re->setLabel('Powtórz hasło:')
                    ->setRequired(true)
                    ->setAttrib('append', array('iconClass' => 'icon-key'))
                    ->addValidator(new \Zend_Validate_StringLength(5, 30), true)
                    ->addValidator(new \WS\Validate\Confirm($password), true);
	
        $roles = $this->getParam('roles');
        $n_roles = array();
        foreach($roles as $role) {
            $n_roles[$role['id']] = $role['name'];
        }
        
        $role = new \Zend_Form_Element_Select('role');
       	$role->setRequired(true)
             ->setLabel('Rola:')
             ->setMultiOptions($n_roles);

        $n_status = array(
            User::STATUS_ACTIVE => 'Aktywny',
            User::STATUS_INACTIVE => 'Nieaktywny',
            User::STATUS_BANNED => 'Zbanowany'
        );
        
        $status = new \Zend_Form_Element_Select('status');
        $status->setRequired(true)
               ->setLabel('Status:')
               ->setMultiOptions($n_status);
        
        $theme = new \Zend_Form_Element_Select('theme');
        $theme->setLabel('Motyw graficzny:')
             ->addMultiOptions($this->getParam('themes'));
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/users"; return false;' );
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));
        $submit->setLabel('Dodaj użytkownika');
        
        $this->addElements(array(
            $username,
            $visibleName,
            $email,
            $name,
            $surname,
            $password,
            $password_re,
            $role,
            $status,
            $theme,
            $submit,
            $cancel
        ));
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
        
        $this->setAction('/users/add')
            ->setMethod('post');
    }
}