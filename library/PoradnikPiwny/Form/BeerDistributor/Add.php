<?php

namespace PoradnikPiwny\Form\BeerDistributor;

use WS\Validate\Doctrine\NoRecordExists,
    WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    PoradnikPiwny\Entities\BeerDistributor;

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
                 ->setLabel('Nazwa dystrybutora:')
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(0, 50), true),
       	     	 	array(new NoRecordExists(array(
                            'class' => '\PoradnikPiwny\Entities\BeerDistributor',
                            'property' => 'name',
                            'exclude' => $exclude
                        )), true)
       	     	 ));
       	$name->getValidator('NoRecordExists')->setMessage('Dystrybutor o danej nazwie istnieje już w bazie', NoRecordExists::ERROR_ENTITY_EXISTS);    		  	
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/beerdistributor"; return false;' );;
        
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));

        if($isEdit) {
            $this->setAction('/beerdistributor/edit/id/' . $id);
            $submit->setLabel('Edytuj dystrybutora');
        } else {
            $this->setAction('/beerdistributor/add');
            $submit->setLabel('Dodaj dystrybutora');
        }
        
        $this->addElements(array(
            $name,
            $cancel,
            $submit
        ));
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
       
        $this->setMethod('post');
    }
    
    public function populateByDistributor(BeerDistributor $distributor)
    {
        $this->name->setValue($distributor->getName());
    }
}