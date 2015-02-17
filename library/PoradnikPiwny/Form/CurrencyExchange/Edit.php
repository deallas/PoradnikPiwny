<?php

namespace PoradnikPiwny\Form\CurrencyExchange;

use WS\Filter\Float as FloatFilter,
    PoradnikPiwny\Entities\CurrencyExchange;

class Edit extends \WS\Form
{
    public function init() 
    {
        $id = $this->getParam('id');
        $idCur = $this->getParam('idCur');

        $multiplier = new \Zend_Form_Element_Text('multiplier');
        $multiplier->setFilters(array('StringTrim', new FloatFilter(array('decimals' => 4))))
                 ->setLabel('Mnożnik:')
                 ->setRequired(true)
                 ->setDecorators(array('ViewHelper'))
                 ->setValidators(array(
                     new \Zend_Validate_Float(),
                     new \Zend_Validate_Between(array('min' => 0.0001, 'max' => 9999.9999))
                 ));
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/currencyexchange/index/id/' . $idCur . '"; return false;' );
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));
        
        $submit->setLabel('Edytuj przelicznik');
        $this->setAction('/currencyexchange/edit/id/' . $id);   
  
        $this->addElements(array(
            $multiplier,
            $submit,
            $cancel
        ));
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
        
        $this->setMethod('post');
    }
    
    public function populateByCurrencyExchange(CurrencyExchange $ex)
    {
        $this->multiplier->setValue($ex->getMultiplier());
    }
}