<?php

namespace WS\Validate;

class OptionalElement extends \Zend_Validate_Abstract
{	
	const ALL_ELEMENT_IS_NOT_EMPTY = 'allElementIsNotEmpty';
	const ALL_ELEMENT_IS_EMPTY = 'allElementIsEmpty';
	
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::ALL_ELEMENT_IS_NOT_EMPTY => 'All element is not empty',
        self::ALL_ELEMENT_IS_EMPTY => 'All element is empty'
    );
	
	/**
	 * @var \Zend_Form_Element
	 */
	protected $element;
	
	/**
	 * @param \Zend_Form_Element $element
	 */
	public function __construct(\Zend_Form_Element $element)
	{
		$this->element = $element;
	}
	
	public function isValid($value) 
	{
		$value = (bool)$value;	
		$value2 = (bool)$this->element->getValue();
			
		if($value)
		{
			if($value2) {
				$this->_error( self::ALL_ELEMENT_IS_NOT_EMPTY );
				return false;
			} else {
				return true;
			}
		} else {
			if($value2) {
				return true;
			} else {
				$this->_error( self::ALL_ELEMENT_IS_EMPTY );
				return false;				
			}
		}
	}
}