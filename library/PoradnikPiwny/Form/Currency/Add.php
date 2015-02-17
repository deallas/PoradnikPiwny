<?php

namespace PoradnikPiwny\Form\Currency;

use WS\Validate\Doctrine\NoRecordExists,
    WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    PoradnikPiwny\Entities\Currency;

class Add extends \WS\Form
{
    public function init() 
    {
        $isEdit = (bool)$this->getParam('isEdit');
        $id = $this->getParam('id');
        
        $exclude = array();
        if($isEdit)
        {
            $exclude = array(
                array(
                    'property' => 'id',
                    'value' => $id
                )
            );
        }
        
    	$name = new \Zend_Form_Element_Text('name');
        $name->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setRequired(true)
                 ->setLabel('Nazwa waluty:')
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(2, 20), true),
       	     	 	array(new NoRecordExists(
                                array(
                                    'class' => '\PoradnikPiwny\Entities\Currency',
                                    'property' => 'name',
                                    'exclude' => $exclude
                                )
                        ), true)
       	     	 ));
        $name->getValidator('NoRecordExists')->setMessage('Nazwa jest już zajęta', NoRecordExists::ERROR_ENTITY_EXISTS);
        
        $symbol = new \Zend_Form_Element_Text('symbol');
        $symbol->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setRequired(true)
                 ->setLabel('Symbol waluty:')
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(0,3), true)
       	     	 ));
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/currency"; return false;' );
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));
        if($isEdit) {
            $submit->setLabel('Edytuj walutę');
            $this->setAction('/currency/edit/id/' . $id);   
        } else {
            $submit->setLabel('Dodaj walutę');
            $this->setAction('/currency/add');             
        }
        
        $this->addElements(array(
            $name,
            $symbol,
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
    
    public function populateByCurrency(Currency $cur)
    {
        $this->name->setValue($cur->getName());
        $this->symbol->setValue($cur->getSymbol());
    }
}