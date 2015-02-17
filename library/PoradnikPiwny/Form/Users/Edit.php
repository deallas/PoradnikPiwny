<?php

namespace PoradnikPiwny\Form\Users;

use WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    PoradnikPiwny\Entities\User,
    WS\Validate\Doctrine\NoRecordExists;

class Edit extends \WS\Form 
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
       	     	 	array(new NoRecordExists(
                                array(
                                    'class' => '\PoradnikPiwny\Entities\User',
                                    'property' => 'username',
                                    'exclude' => array(
                                        array(
                                            'property' => 'id',
                                            'value' => $this->getParam('id')
                                        )
                                    )
                                )
                        ), true),
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
       	     	 	array(new NoRecordExists(
                                array(
                                    'class' => '\PoradnikPiwny\Entities\User',
                                    'property' => 'email',
                                    'exclude' => array(
                                        array(
                                            'property' => 'id',
                                            'value' => $this->getParam('id')
                                        )
                                    )
                                )
                        ), true),
                 ));

       	$email->getValidator('NoRecordExists')->setMessage('Inny użytkownik jest już zarejestrowany na podanym adresie email', NoRecordExists::ERROR_ENTITY_EXISTS);
        
        $visibleName = new \Zend_Form_Element_Text('visibleName');
        $visibleName->setFilters(array('StringTrim', 'StringToLower', new HtmlspecialcharsFilter()))
                 ->setRequired(true)
                 ->setLabel('Podpis:')
                 ->setAttrib('append', array('iconClass' => 'icon-user'))
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(3, 50), true),
       	     	 	array(new NoRecordExists(
                                array(
                                    'class' => '\PoradnikPiwny\Entities\User',
                                    'property' => 'visibleName',
                                    'exclude' => array(
                                        array(
                                            'property' => 'id',
                                            'value' => $this->getParam('id')
                                        )
                                    )
                                )
                        ), true),
       	     	 	array('Alnum')
       	     	 ));
        $visibleName->getValidator('NoRecordExists')->setMessage('Dany podpis jest już zajęty', NoRecordExists::ERROR_ENTITY_EXISTS);				
        
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
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit');
        $submit->setLabel('Edytuj użytkownika')
               ->setAttrib('class', 'btn btn-primary');
        
        $this->addElements(array(
            $username,
            $visibleName,
            $email,
            $name,
            $surname,
            $role,
            $status,
            $theme,
            $cancel,
            $submit
        ));
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
        
        $this->setAction('/users/edit/id/' . $this->getParam('id'))
             ->setMethod('post');
    }
    
    public function populateByUser(User $user) 
    {
        $this->username->setValue($user->getUsername());
        $this->visibleName->setValue($user->getVisibleName());
        $this->email->setValue($user->getEmail());
        $this->role->setValue($user->getRole()->getId());
        $this->status->setValue($user->getStatus());
        
        $data = $user->getMetadataArray();

        $this->name->setValue((isset($data['name'])) ? $data['name'] : '') ;
        $this->surname->setValue((isset($data['surname'])) ? $data['surname'] : '') ;
        $this->theme->setValue((isset($data['theme'])) ? $data['theme'] : '');
    }
}