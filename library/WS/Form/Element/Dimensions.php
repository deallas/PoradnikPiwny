<?php

class WS_Form_Element_Dimensions extends Zend_Form_Element_Xhtml
{
    public $helper = 'formDimensions'; 

    public function isValid($value, $context = null)
    {
    	$this->setAttrib('values', $value);
        
        $value = $value['width'] . 'x' . $value['height'];

        return parent::isValid($value, $context);
    }

}