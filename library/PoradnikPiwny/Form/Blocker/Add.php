<?php

namespace PoradnikPiwny\Form\Blocker;

use PoradnikPiwny\Entities\BlockerRule,
    PoradnikPiwny\Validate\Doctrine\BlockerIpResgroupUnique;

class Add extends \WS\Form 
{
    public function init() 
    {
    	$isEdit = (bool)$this->getParam('isEdit');
    	
        $ip = new \Zend_Form_Element_Text('ip');
        $ip->addValidator(new \Zend_Validate_Ip())
            ->setRequired(true)
            ->setLabel('Ip:');
        
        $dateExpired = new \WS\Form\Element\DateTime('dateExpired');
        $dateExpired->setLabel('Data wygaśnięcia:');
        $dateExpired->setDescription('Reguła nigdy nie utraci ważności jeśli pole pozostanie puste');
        $dateExpired->setValidators(array(
            new \Zend_Validate_Date('dd/MM/YYYY HH:mm')
        ));
	
        $a_resgroup = array(0 => '-');
        foreach($this->getParam('resgroup') as $resgroup)
        {
            $a_resgroup[$resgroup->getId()] = $resgroup->getName();
        }
        
        $resgroup = new \Zend_Form_Element_Select('resgroup');
        $resgroup->isRequired(true);
        $resgroup->setLabel('Zasób:');
        $resgroup->addMultiOptions($a_resgroup);

        if($isEdit) {
            $blockerId = $this->getParam('id');
        } else {
            $blockerId = null;
        }
        $ip->addValidator(new BlockerIpResgroupUnique($resgroup, $blockerId));
        
        $priority = new \Zend_Form_Element_Text('priority');
        $priority->setLabel('Priorytet:')
                 ->setValue(1)
                 ->setDescription('Numeryczne określenie priorytetu reguły. Im większy tym bardziej jest on ważny.')
                 ->setValidators(array(
                     new \Zend_Validate_Int()
                 ));
		
        $message = new \Zend_Form_Element_Textarea('message');
        $message->setLabel('Powód:')
                ->setAttrib('class', 'input-textarea');
		
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));
        if($isEdit) {
            $submit->setLabel('Edytuj regułę');
        } else {
            $submit->setLabel('Dodaj regułę');
        }

        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/blocker"; return false;' );;
 
        
        $this->addElements(array(
            $resgroup,
            $ip,
            $priority,
            $dateExpired,
            $message,
            $cancel,
            $submit
        ));
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
        
        if($isEdit) {
            $this->setAction('/blocker/edit/id/' . $blockerId);
        } else {
            $this->setAction('/blocker/add');
        }
    	$this->setMethod('post');
    }
    
    public function populateByRule(BlockerRule $blocker)
    {
        $resgroup = $blocker->getAclResgroup();
        if($resgroup != null)
        {
            $this->resgroup->setValue($resgroup->getId());
        }
        
        $this->ip->setValue($blocker->getIp());
        $this->message->setValue($blocker->getMessage());
        $this->priority->setValue($blocker->getPriority());
        
        $date = $blocker->getDateExpired();
        if($date != null)
        {
            $this->dateExpired->setAttrib('values', array(
                'year' => $date->toString('YYYY'),
                'month' => $date->toString('MM'),
                'day' => $date->toString('dd'),
                'hour' => $date->toString('HH'),
                'minute' => $date->toString('mm')
            ));
        }
    }
}