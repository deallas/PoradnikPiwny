<?php

namespace WS\Form\Element;

class MinMax extends \Zend_Form_Element_Xhtml
{
    public $helper = 'formMinMax'; 
    
    public function setValue($value) {
        if(isset($value['min']))
        {
            $this->setMin($value['min']);
        }
        
        if(isset($value['max']))
        {
            $this->setMax($value['max']); 
        }
        
        if(isset($value['symbol']))
        {
            $this->setSymbol($value['symbol']);
        }
        
        if(isset($value['placeholder']))
        {
            $this->setPlaceholder($value['placeholder']);
        }
        
        return $this;
    }
    
    public function getValue() {
        return array(
            'min' => $this->getAttrib('min'),
            'max' => $this->getAttrib('max'),
            'symbol' => $this->getAttrib('symbol'),
            'placeholder' => $this->getAttrib('placeholder')
        );
    }
    
    public function setMin($min)
    {
        $this->setAttrib('min', $min);
        
        return $this;
    }
    
    public function getMin()
    {
        return $this->getAttrib('min');
    }
    
    public function setMax($max)
    {
        $this->setAttrib('max', $max);
                
        return $this;
    }
    
    public function getMax()
    {
        return $this->getAttrib('max');
    }
    
    public function setSymbol($symbol)
    {
        $this->setAttrib('symbol', $symbol);
                
        return $this;
    }
    
    public function getSymbol()
    {
        return $this->getAttrib('symbol');
    }
    
    public function setPlaceholder($placeholder)
    {
        $this->setAttrib('placeholder', $placeholder);
                
        return $this;
    }
    
    public function getPlaceholder()
    {
        return $this->getAttrib('placeholder');
    }    
}