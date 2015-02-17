<?php

namespace PoradnikPiwny\Form\Page;

use WS\Filter\Htmlspecialchars;

class Edit extends \WS\Form 
{
    public function init() 
    {
        $title = new \Zend_Form_Element_Text('title');
        $title->setFilters(array('StringTrim', new Htmlspecialchars()))
            ->setLabel('Tytuł:')
            ->addValidator(new \Zend_Validate_StringLength(0, 100), true);				 

       	$separator = new \Zend_Form_Element_Text('separator');
       	$separator->setFilters(array(new Htmlspecialchars()))
            ->setLabel('Separator w tytule:')
            ->addValidator(new \Zend_Validate_StringLength(0,5), true);

        $keywords = new \Zend_Form_Element_Text('keywords');
        $keywords->setFilters(array('StringTrim', new Htmlspecialchars()))
            ->setLabel('Słowa kluczowe:')
            ->addValidator(new \Zend_Validate_StringLength(0, 200), true);
				 
        $desc = new \Zend_Form_Element_Text('description');
        $desc->setFilters(array('StringTrim', new Htmlspecialchars()))
            ->setLabel('Opis:')
            ->addValidator(new \Zend_Validate_StringLength(0, 200), true);			 				
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick',  'document.location = "' . ADMIN_APPLICATION_URL . '/page"; return false;' );
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit');
        $submit->setLabel('Zapisz ustawienia')
               ->setAttrib('class', 'btn btn-primary');
        
        $this->addElements(array(
            $title,
            $separator,
            $keywords,
            $desc,
            $cancel,
            $submit
        ));
        
        $this->addDisplayGroup(array(
            'submit',
            'cancel'
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');

        $this->setAction('/page/edit')
            ->setMethod('post');
    }
}