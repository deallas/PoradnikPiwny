<?php

namespace WS\Validate;

class PriceAndSizeOfBottle extends \Zend_Validate_Abstract
{	
    const INVALID_NUMBER = 'invalidNumber';
    const INVALID_CURRENCY = 'invalidCurrency';
    const INVALID_SIZE_OF_BOTTLE = 'invalidSizeOfBottle';
    const INVALID_MIN = 'invalidMin';
    const INVALID_MAX = 'invalidMax';
    const MINMAX_NUMBER_NOT_BETWEEN = 'minMaxNumberNotBetween';
    const MINMAX_SIZE_OF_BOTTLE_NOT_BETWEEN = 'minSizeOfBottleMaxNotBetween';
    
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_NUMBER => 'Niepoprawny format ceny',
        self::INVALID_CURRENCY => 'Dana waluta nie istnieje',
        self::INVALID_SIZE_OF_BOTTLE => 'Niepoprawny format rozmiaru butelki',
        self::MINMAX_NUMBER_NOT_BETWEEN => "Wpisana cena nie zawiera się pomiędzy %minNumber%, a %maxNumber%",
        self::MINMAX_SIZE_OF_BOTTLE_NOT_BETWEEN => "Wpisana cena nie zawiera się pomiędzy %minSizeOfBottle%, a %maxSizeOfBottle%",
        self::INVALID_MIN => 'Niepoprawna wartość minimalna',
        self::INVALID_MAX => 'Niepoprawna wartość maksymalna',
    );
	
    protected $_messageVariables = array(
        'minSizeOfBottle' => '_minSizeOfBottle',
        'maxSizeOfBottle' => '_maxSizeOfBottle',
        'minNumber' => '_minNumber',
        'maxNumber' => '_maxNumber'
    );
    
    /**
     * @var array
     */
    protected $_currencies;
    
    protected $_minSizeOfBottle = 0;
    
    protected $_maxSizeOfBottle = 100;
    
    protected $_minNumber = 0;
    
    protected $_maxNumber = 100;
    
    protected $_decimalsNumber = 2;
    
    public function __construct($currencies = array(), $options = array()) 
    {
        $this->_currencies = $currencies;
        
        if(isset($options['number']))
        {
            if(isset($options['number']['min']))
            {
                $this->_minNumber = $options['number']['min'];
            }
            
            if(isset($options['number']['max']))
            {
                $this->_maxNumber = $options['number']['max'];
            }
            
            if(isset($options['number']['decimals']))
            {
                $this->_decimalsNumber = $options['number']['decimals'];
            }
        }
        
        if(isset($options['sizeOfBottle']))
        {
            if(isset($options['sizeOfBottle']['min']))
            {
                $this->_minSizeOfBottle = $options['sizeOfBottle']['min'];
            }
            
            if(isset($options['sizeOfBottle']['max']))
            {
                $this->_maxSizeOfBottle = $options['sizeOfBottle']['max'];
            }
        }
    }
    
    public function isValid($value) 
    {      
        $this->_setValue($value);
        
        /* ------------------------------------------------------------------ */
        
        if(!isset($this->_currencies[$value['currency']]))
        {
            $this->_error(self::INVALID_CURRENCY);
            return false;
        }
        
        /* ------------------------------------------------------------------ */

        $f1 = new \WS\Filter\Float(array('decimals' => $this->_decimalsNumber));
        $v3 = new \Zend_Validate_Float();
        $number = $f1->filter($value['number']);
        
        if(!$v3->isValid($number))
        {
            $this->_error(self::INVALID_NUMBER);
            return false;
        }
        
        if($number < $this->_minSizeOfBottle || $number > $this->_maxSizeOfBottle)
        {
            $this->_error(self::MINMAX_NUMBER_NOT_BETWEEN);
            return false; 
        }
        
        /* ------------------------------------------------------------------ */
        $v2 = new \Zend_Validate_Int();
        if(!$v2->isValid($value['sizeOfBottle']))
        {
            $this->_error(self::INVALID_SIZE_OF_BOTTLE);
            return false;
        }
        
        if($value['sizeOfBottle'] < $this->_minSizeOfBottle
                || $value['sizeOfBottle'] > $this->_maxSizeOfBottle)
        {
            $this->_error(self::MINMAX_SIZE_OF_BOTTLE_NOT_BETWEEN);
            return false; 
        }   
        
        return true;
    }
    
    public function getMinSizeOfBottle() {
        return $this->_minSizeOfBottle;
    }

    public function setMinSizeOfBottle($minSizeOfBottle) {
        $this->_minSizeOfBottle = $minSizeOfBottle;
        
        return $this;
    }

    public function getMaxSizeOfBottle() {
        return $this->_maxSizeOfBottle;
    }

    public function setMaxSizeOfBottle($maxSizeOfBottle) {
        $this->_maxSizeOfBottle = $maxSizeOfBottle;
        
        return $this;
    }

    public function getMinNumber() {
        return $this->_minNumber;
    }

    public function setMinNumber($minNumber) {
        $this->_minNumber = $minNumber;
        
        return $this;
    }

    public function getMaxNumber() {
        return $this->_maxNumber;
    }

    public function setMaxNumber($maxNumber) {
        $this->_maxNumber = $maxNumber;
        
        return $this;
    }
}