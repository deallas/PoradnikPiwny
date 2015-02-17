<?php

namespace WS\Form\Element;

class PriceAndSizeOfBottle extends \Zend_Form_Element_Xhtml
{
    public $helper = 'formPriceAndSizeOfBottle'; 

    public function __construct($spec, $currencies = array(), $options = null) {
        parent::__construct($spec, $options);

        $this->setAttrib('currencies', $currencies);
    }
    
    public function setValue($value) 
    {
        if(isset($value['number']))
        {
            $this->setNumber($value['number']);
        }
        
        if(isset($value['currency']))
        {
            $this->setCurrency($value['currency']); 
        }
        
        if(isset($value['sizeOfBottle']))
        {
            $this->setSizeOfBottle($value['sizeOfBottle']);
        }
        
        return $this;
    }
    
    public function getValue() 
    {
        return array(
            'number' => $this->getAttrib('number'),
            'currency' => $this->getAttrib('currency'),
            'sizeOfBottle' => $this->getAttrib('sizeOfBottle')
        );
    }
    
    public function setNumber($number)
    {
        $this->setAttrib('number', $number);
    }
    
    public function getNumber()
    {
        return $this->getAttrib('number');
    }
    
    public function setCurrency($currency)
    {
        $this->setAttrib('currency', $currency);
    }
    
    public function getCurrency()
    {
        return $this->getAttrib('currency');
    }
    
    public function setSizeOfBottle($sizeOfBottle)
    {
        $this->setAttrib('sizeOfBottle', $sizeOfBottle);
    }
    
    public function getSizeOfBottle()
    {
        return $this->getAttrib('sizeOfBottle');
    }
}