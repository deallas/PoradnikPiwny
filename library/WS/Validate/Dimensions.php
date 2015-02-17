<?php

namespace WS\Validate;

class Dimensions extends \Zend_Validate_Abstract
{	
    const INVALID_DIMENSIONS = 'invalidDimensions';
    const INVALID_WIDTH = 'invalidWidth';
    const INVALID_HEIGHT = 'invalidHeight';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_DIMENSIONS => 'Invalid dimensions',
        self::INVALID_WIDTH => 'Invalid width',
        self::INVALID_HEIGHT => 'Invalid height'
    );
		
    public function isValid($value) 
    {
        $this->_setValue($value);
        
        $values = explode('x', $value);
        if(count($values) != 2)
        {
            $this->_error( self::INVALID_DIMENSIONS );
            return false;
        }
        $validator = new \Zend_Validate_Int();
        if(!$validator->isValid($values[0]) && !empty($values[1]))
        {
            $this->_error( self::INVALID_WIDTH );
            return false;			
        }

        if(!$validator->isValid($values[1]) && !empty($values[0]))
        {
            $this->_error( self::INVALID_HEIGHT );
            return false;				
        }

        return true;
    }
}