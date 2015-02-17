<?php

namespace PoradnikPiwny\Form\Settings;

class Edit extends \WS\Form 
{	
    public function init() 
    {	
    	$blocker = new \Zend_Form_Element_Checkbox('blocker');
    	$blocker->setLabel('Blokowanie użytkowników:');
    	
    	$blocker_max = new \Zend_Form_Element_Text('blocker_max_attempts');
        $blocker_max->setFilters(array('StringTrim'))
            ->setValidators(array(
                array('Int', true),
                array('GreaterThan', false, array(
                    'min' => 0
                ))
            ))
            ->setLabel('Blokowanie użytkowników po próbie:');
                		
        $blocker_time = new \Zend_Form_Element_Select('blocker_time');
       	$blocker_time->setLabel('Blokowanie na:')
            ->setMultiOptions(array(
                    '60' => '1 minutę',
                    '120' => '2 minuty',
                    '300' => '5 minut',
                    '900' => '15 minut',
                    '1800' => '30 minut',
                    '2700' => '45 minut',
                    '3600' => '1 godzinę'
            ));

        $blocker_attempt_time = new \Zend_Form_Element_Select('blocker_attempt_time');
       	$blocker_attempt_time->setLabel('Zapamiętanie prób logowania na:')
            ->setMultiOptions(array(
                    '60' => '1 minutę',
                    '120' => '2 minuty',
                    '300' => '5 minut',
                    '900' => '15 minut',
                    '1800' => '30 minut',
                    '2700' => '45 minut',
                    '3600' => '1 godzinę'
            ))
            ->setValidators(array(new \WS\Validate\GreaterThanElement($blocker_time)));   

        $blocker_attempt_time->getValidator('GreaterThanElement')
            ->setMessage('Czas zapamiętania prób logowania powinien być większy od czasu blokowania', 
                        \WS\Validate\GreaterThanElement::NOT_GREATER);
                
        $captcha = new \Zend_Form_Element_Checkbox('captcha');
        $captcha->setLabel('Captcha:');      
                
        $captcha_min = new \Zend_Form_Element_Text('captcha_min_attempts');
        $captcha_min->setFilters(array('StringTrim'))
            ->setValidators(array(
                array('Int', true),
                array('GreaterThan', false, array(
                    'min' => 0
                ))
            ))
            ->setLabel('Włączenie captchy po próbie:');
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/settings"; return false;' );

        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit');
        $submit->setLabel('Zapisz ustawienia')
               ->setAttrib('class', 'btn btn-primary');
        
        $this->addElements(array(
            $blocker,
            $blocker_max,
            $blocker_time,
            $blocker_attempt_time,
            $captcha,
            $captcha_min,
            $cancel,
            $submit
        ));
             
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
        
        $this->setAction('/settings/edit')
            ->setMethod('post');
    }
}