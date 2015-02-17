<?php

namespace PoradnikPiwny\Form\Account;

use WS\Filter\Htmlspecialchars,
    WS\Validate\Doctrine\NoRecordExists,
    PoradnikPiwny\Entities\User;

class Settings extends \WS\Form 
{	
    public function init() 
    {
        $visibleName = new \Zend_Form_Element_Text('visibleName');
        $visibleName->setFilters(array('StringTrim'))
            ->setLabel('Nazwa użytkownika:')
            ->setRequired(true)
            ->setAttrib('append', array('iconClass' => 'icon-user'))
            ->setValidators(array(
                array(new \Zend_Validate_StringLength(3, 50), true),
                array(new NoRecordExists(array(
                    'class' => '\PoradnikPiwny\Entities\User', 
                    'property' => 'visibleName',
                    'exclude' => array(
                        array(
                            'property' => 'id',
                            'value' => $this->getParam('id')
                        )
                    )
                )), true),
            ));

       	$visibleName->getValidator('NoRecordExists')->setMessage('Nazwa użytkownika jest już zajęta', NoRecordExists::ERROR_ENTITY_EXISTS);
        
        $name = new \Zend_Form_Element_Text('name');
        $name->setFilters(array('StringTrim', new Htmlspecialchars()))
            ->setValidators(array(
                'Alpha', 
                array('StringLength', false, array(3, 50))
            ))
            ->setLabel('Imię:');
				 
        $surname = new \Zend_Form_Element_Text('surname');
        $surname->setFilters(array('StringTrim', new Htmlspecialchars()))
            ->setValidators(array(
                'Alpha', 
                array('StringLength', false, array(3, 50))
            ))
            ->setLabel('Nazwisko:');
        		 
        $theme = new \Zend_Form_Element_Select('theme');
        $theme->setLabel('Motyw graficzny:')
             ->addMultiOptions($this->getParam('themes'));
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '"; return false;' );

        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit');
        $submit->setLabel('Zapisz ustawienia')
               ->setAttrib('class', 'btn btn-primary');
        
        $this->addElements(array(
            $visibleName,
            $name,
            $surname,
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
        
        $this->setAction('/account/settings')
            ->setMethod('post');
    }
    
    /**
     * @param \PoradnikPiwny\Entities\User $user
     * @param array $userMeta
     */
    public function populateByUser(User $user, $userMeta) 
    {
        $this->visibleName->setValue($user->getVisibleName());

        $this->name->setValue((isset($userMeta['name'])) ? $userMeta['name'] : '') ;
        $this->surname->setValue((isset($userMeta['surname'])) ? $userMeta['surname'] : '') ;
        $this->theme->setValue((isset($userMeta['theme'])) ? $userMeta['theme'] : 'default') ;
    }
}