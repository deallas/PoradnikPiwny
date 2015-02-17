<?php

namespace WS\Validate;

class GreaterThanElement extends \Zend_Validate_Abstract
{	
	const NOT_GREATER = 'notGreaterThan';
	
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_GREATER => "'%value%' is not greater than '%min%'",
    );
	
    /**
     * Minimum value
     *
     * @var mixed
     */
    protected $_min;
    
    /**
     * @var mixed
     */
    protected $_value;
    
    /**
     * @var array
     */
    protected $_messageVariables = array(
        'min' => '_min'
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

	/**
     * Returns the min option
     *
     * @return mixed
     */
    public function getMin()
    {
        return $this->_min;
    }

    /**
     * Sets the min option
     *
     * @param  mixed $min
     * @return \Zend_Validate_GreaterThan Provides a fluent interface
     */
    public function setMin($min)
    {
        $this->_min = $min;
        return $this;
    }
	
	public function isValid($value) 
	{
		$this->_setValue($value);
		$this->_min = $this->element->getValue();
		if($this->_min >= $value)
		{
			$this->_error(self::NOT_GREATER);
            return false;
		}
		
		return true;
	}
}