<?php

class WS_Form_Element_Date extends Zend_Form_Element_Xhtml
{
    public $helper = 'formDate'; 

    public function isValid($value, $context = null)
    {
        $this->setAttrib('values', $value);
        
        $empty = true;

        if(empty($value['day']))
        {
            $value['day'] = '--';
        } else {
            $empty = false;
        }
        
        if(empty($value['month']))
        {
            $value['month'] = '--';
        } else {
            $empty = false;
        }
        
        if(empty($value['year']))
        {
            $value['year'] = '----';
        } else {
            $empty = false;
        }

        if($empty) return parent::isValid('', $context);
        
        $value = $value['day'] . '/' . $value['month'] . '/' . $value['year'];

        return parent::isValid($value, $context);
    }

}