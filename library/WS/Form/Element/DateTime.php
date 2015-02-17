<?php

namespace WS\Form\Element;

class DateTime extends \Zend_Form_Element_Xhtml
{
    public $helper = 'formDateTime'; 

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

        if(empty($value['hour']))
        {
            $value['hour'] = '--';
        } else {
            $empty = false;
        }
        if(empty($value['minute']))
        {
            $value['minute'] = '--';
        } else {
            $empty = false;
        }

        if($empty) return parent::isValid('', $context);
        
        $value = $value['day'] . '/' . $value['month'] . '/' . $value['year'] . ' ' . $value['hour'] . ':' . $value['minute'];
        
        return parent::isValid($value, $context);
    }
    
    /**
     * @return \Zend_Date 
     */
    public function getDate()
    {
        if($this->getValue() == '') {
            return null;
        }
        $values = $this->getAttrib('values');
        $date = new \Zend_Date();
        $date->setHour($values['hour']);
        $date->setMinute($values['minute']);
        $date->setDay($values['day']);
        $date->setMonth($values['month']);
        $date->setYear($values['year']);
        $date->setSecond(0);
        
        return $date;
    }
}