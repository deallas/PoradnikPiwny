<?php 

namespace WS\Filter;

class Float implements \Zend_Filter_Interface 
{
    private $decimals = 2;
    
    public function __construct($options = array())
    {
        if(isset($options['decimals']))
            $this->decimals = $options['decimals'];
    }
    
    /**
     * @param  string $value
     * @return float
     */
    public function filter($value) 
    {
        if(empty($value))
            return null;
        $value = (float)number_format(
            floatval(str_replace(',', '.', $value)),
            $this->decimals,
            '.',
            ''
        );
        
        if($value == 0)
            return null;

    	return $value;
    }
}