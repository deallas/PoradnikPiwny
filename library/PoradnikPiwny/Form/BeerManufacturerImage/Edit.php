<?php

namespace PoradnikPiwny\Form\BeerManufacturerImage;

use WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    PoradnikPiwny\Entities\BeerManufacturerImage;

class Edit extends \WS\Form
{
    public function init() 
    {   
        $beerManId = $this->getParam('beerManId');
        $beerManImageId = $this->getParam('beerManImageId');
        
        $title = new \Zend_Form_Element_Text('title');
        $title->setFilters(array('StringTrim', new HtmlspecialcharsFilter()))
                 ->setLabel('Tytuł zdjęcia:')
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(0, 200), true)
       	     	 ));
        
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
        $submit->setLabel('Edytuj zdjęcie');
        
        $this->addElements(array(
            $title,
            $primary,
            $status,
            $cancel,
            $submit   
        ));
        
        $this->setAction('/beermanufacturerimage/edit/id/' . $beerManImageId)
             ->setMethod('post');
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
    }
    
    public function populateByBeerManufacturerImage(BeerManufacturerImage $bi)
    {
        $this->title->setValue($bi->getTitle());
        
        switch($bi->getStatus()) {
            case BeerManufacturerImage::STATUS_NIEWIDOCZNY:
                $this->status->setValue(false);
                break;
            case BeerManufacturerImage::STATUS_WIDOCZNY:
                $this->status->setValue(true);
                break;
        }
        
        $biPrimaryId = $bi->getBeerManufacturer()->getBeerManufacturerImage()->getId();
        
        if($biPrimaryId == $bi->getId()) {
            $this->primary->setValue(true);
        } else {
            $this->primary->setValue(false);
        }
    }
}