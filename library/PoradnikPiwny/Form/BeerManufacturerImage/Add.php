<?php

namespace PoradnikPiwny\Form\BeerManufacturerImage;

use WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter;

class Add extends \WS\Form
{
    public function init() 
    {   
        $beerManId = $this->getParam('beerManId');
        
        $title = new \Zend_Form_Element_Text('title');
        $title->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setLabel('Tytuł zdjęcia:')
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(0, 200), true)
       	     	 ));
        
        // --------->
        
        $size = \WS\Tool::getMaxUploadedSize(1024*1024*5); // 5MB
        $desc = sprintf($this->getTranslator()->_('Maksymalny rozmiar pliku: %s'), \WS\Tool::getXBytesByBit($size));
        
        $image = new \Twitter_Bootstrap_Form_Element_File('img');
        $image->setRequired(true)
                ->setLabel('Zdjęcie:')
                ->addValidator('NotEmpty', true)
                ->addValidator('Count',true, 1)
                ->addValidator('Size',true, $size)   // 5MB            
                ->addValidator('Extension',false,'jpg,gif,png,jpeg,jpe')
                ->setDescription($desc);
        
        // --------->
        
        $primary = new \Zend_Form_Element_Checkbox('primary');
        $primary->setLabel('Ustaw jako główne:');       
        
        // --------->
        
        $status = new \Zend_Form_Element_Checkbox('status');
        $status->setLabel('Opublikuj:')
               ->setValue(true);
        
        // --------->
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/beermanufacturerimage/index/id/' . $beerManId . '"; return false;' );;
        
        // --------->
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));
        $submit->setLabel('Dodaj zdjęcie');
        
        $this->addElements(array(
            $title,
            $image, 
            $primary,
            $status,
            $cancel,
            $submit   
        ));
        
        $this->setAction('/beermanufacturerimage/add/id/' . $beerManId)
             ->setMethod('post');
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
    }
}