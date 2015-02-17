<?php

namespace WS\Validate;

class MinMax extends \Zend_Validate_Abstract
{	
    const INVALID_MIN = 'invalidMin';
    const INVALID_MAX = 'invalidMax';
    const MAX_LOWER_THAN_MIN = 'maxLowerThanMin';
    const MINMAX_NOT_BETWEEN = 'minMaxNotBetween';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::MINMAX_NOT_BETWEEN => "Wpisana wartość nie zawiera się pomiędzy %min%, a %max%",
        self::INVALID_MIN => 'Niepoprawna wartość minimalna',
        self::INVALID_MAX => 'Niepoprawna wartość maksymalna',
        self::MAX_LOWER_THAN_MIN => 'Wartość maksymalna nie może być mniejsza od wartości minimalnej'
    );
    
    protected $_messageVariables = array(
        'min' => '_min',
        'max' => '_max'
    );
    
    protected $_min = 0;
    
    protected $_max = 100;
    
    protected $_decimals = 2;
    
    public function __construct($options = array()) {
        if(isset($options['values']))
        {
            if(isset($options['values']['min']))
            {
                $this->_min = $options['values']['min'];
            }
            
            if(isset($options['values']['max']))
            {
                $this->_max = $options['values']['max'];
            }
        }
    }
    
    public function isValid($value) 
    {
        $this->_setValue($value);
        
        $f1 = new \WS\Filter\Float(array('decimals' => $this->_decimals));
        $v1 = new \Zend_Validate_Float();
        $min = $f1->filter($value['min']);
        if(!$v1->isValid($min) && !empty($min)) {
            $this->_error(self::INVALID_MIN);
            return false;
        }
        
        if(!$this->_checkBetween($min))
        {
            $this->_error(self::MINMAX_NOT_BETWEEN);
            return false; 
        }
        
        $max = $f1->filter($value['max']);
        if(!$v1->isValid($max) && !empty($max))
        {
            $this->_error(self::INVALID_MAX);
            return false;
        }
        
        if(!$this->_checkBetween($max))
        {
            $this->_error(self::MINMAX_NOT_BETWEEN);
            return false; 
        }
        
        if(!empty($min) && !empty($max))
        {
            if($max < $min)
            {
                $this->_error(self::MAX_LOWER_THAN_MIN);
                return false;            
            }   
        }
        
        return true;
    }
    
    public function getMin() {
        return $this->_min;
    }

    public function setMin($min) {
        $this->_min = $min;
        return $this;
    }

    public function getMax() {
        return $this->_max;
    }

    public function setMax($max) {
        $this->_max = $max;
        return $this;
    }
        
    private function _checkBetween($value)
    {
        if($value > $this->_max || $value < $this->_min)
        {
            return false;
        }
        
        return true;
    }
}