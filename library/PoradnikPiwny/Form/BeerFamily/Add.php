<?php

namespace PoradnikPiwny\Form\BeerFamily;

use \WS\Validate\Doctrine\NoRecordExists,
    \WS\Filter\Htmlspecialchars as HtmlspecialcharsFilter,
    \WS\Tool;

class Add extends \WS\Form
{
    private $_parents = array();
    
    public function init() 
    {
        $isEdit = (bool)$this->getParam('isEdit');
        $id = $this->getParam('id');
        $beerfamily = $this->getParam('beerfamily');
        
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
                 ->setLabel('Nazwa potomka:')
       	     	 ->setValidators(array(
       	     	 	array(new \Zend_Validate_StringLength(0, 50), true),
       	     	 	array(new NoRecordExists(array(
                            'class' => '\PoradnikPiwny\Entities\BeerFamily',
                            'property' => 'name',
                            'exclude' => $exclude
                        )), true)
       	     	 ));
       	$name->getValidator('NoRecordExists')->setMessage('Potomek o danej nazwie istnieje już w bazie', NoRecordExists::ERROR_ENTITY_EXISTS);    		  	
        
        if($beerfamily != null)
        {
            $name->setValue($beerfamily->getName());
        }
        
        $parents = $this->getParam('parents');
        $arr = array_reverse($parents, true);
        $arr[0] = '';
        $parents = array_reverse($arr, true); 
        
        $parent = new \Zend_Form_Element_Select('parent');
        $parent->setLabel('Rodzice:')
               ->setRequired(true)
               ->addMultiOptions($parents);
        
        $addParent = new \Twitter_Bootstrap_Form_Element_Button('addParent',
                array(
                    'class' => 'btn-mini',
                    'icon'  => 'plus',
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS
                ));
        $addParent->setLabel('Dodaj');     
        
        $cancel = new \Twitter_Bootstrap_Form_Element_Button('cancel');
        $cancel->setLabel('Anuluj')
               ->setAttrib('onClick', 'document.location = "' . ADMIN_APPLICATION_URL . '/beerfamily"; return false;' );;
        
        
        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit',
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
                ));
        if($isEdit) {
            $this->setAction('/beerfamily/edit/id/' . $id);
            $submit->setLabel('Edytuj potomka');
        } else {
            $this->setAction('/beerfamily/add');
            $submit->setLabel('Dodaj potomka');
        }
        
        $this->addElements(array(
            $name,
            $parent
        ));
        
        $i = 0;
        if($beerfamily != null) {
            $oldParents = $beerfamily->getParents();
            if(isset($oldParents[0]))
            {
                $this->parent->setValue($oldParents[0]->getId());
                unset($oldParents[0]);
            }
            
            foreach($oldParents as $family)
            {
                $parent_a = new \Zend_Form_Element_Select('parent_' . $i++, array(
                    'class' => 'controls-beerfamily'
                ));
                $parent_a->addMultiOptions($parents)
                         ->setValue($family->getId());

                $this->addElement($parent_a);
            }             
        } else {
            $request = $this->getParam('request');
            
            foreach($request as $k => $v)
            {
                if(!Tool::startsWith($k, 'parent_'))
                    continue;
                if($v == 0) 
                    continue;
                
                $this->_parents[] = $v;

                $parent_a = new \Zend_Form_Element_Select($k, array(
                    'class' => 'controls-beerfamily'
                ));
                $parent_a->addMultiOptions($parents)
                         ->setValue($v);

                $this->addElement($parent_a);
            }   
        }

        $this->addElements(array(
            $addParent,
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
    
    public function getParentIds()
    {
        $value = $this->parent->getValue();
        if($value == 0)
        {
            return $this->_parents;
        }
        
        return array_merge($this->_parents, array($value));
    }
}