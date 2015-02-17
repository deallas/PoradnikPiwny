<?php

namespace PoradnikPiwny\Form\BeerImage;

use WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    PoradnikPiwny\Entities\BeerImage;

class Edit extends \WS\Form
{
    public function init() 
    {   
        $beerId = $this->getParam('beerId');
        $beerImageId = $this->getParam('beerImageId');
        
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
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/beerimage/index/id/' . $beerId . '"; return false;' );;
        
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
        
        $this->setAction('/beerimage/edit/id/' . $beerImageId)
             ->setMethod('post');
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');
    }
    
    public function populateByBeerImage(BeerImage $bi)
    {
        $this->title->setValue($bi->getTitle());
        
        switch($bi->getStatus()) {
            case BeerImage::STATUS_NIEWIDOCZNY:
                $this->status->setValue(false);
                break;
            case BeerImage::STATUS_WIDOCZNY:
                $this->status->setValue(true);
                break;
        }
        
        $biPrimaryId = $bi->getBeer()->getImage()->getId();
        
        if($biPrimaryId == $bi->getId()) {
            $this->primary->setValue(true);
        } else {
            $this->primary->setValue(false);
        }
    }
}