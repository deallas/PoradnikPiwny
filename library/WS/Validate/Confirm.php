<?php

namespace WS\Validate;

class Confirm extends \Zend_Validate_Abstract 
{
    protected $_matchedField;

    const NOT_CONFIRMED = 'notConfirmed';

    protected $_messageTemplates = array(
        self::NOT_CONFIRMED => 'Entered values are different'
    );

    public function __construct(\Zend_Form_Element $matchedField) 
    {
        $this->_matchedField = $matchedField;
    }

    public function isValid($value) 
    {
        $this->_setValue($value);
        
        if($this->_matchedField->getValue() == $value) {
            return true;
        } else {
            $this->_error( self::NOT_CONFIRMED );
            return false;
        }
    }
}