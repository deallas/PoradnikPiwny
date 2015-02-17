<?php

namespace WS\Filter;

class Htmlspecialchars implements \Zend_Filter_Interface 
{
    /**
     * Corresponds to second htmlspecialchars() argument
     *
     * @var integer
     */
    protected $_quoteStyle;

    /**
     * Corresponds to third htmlspecialchars() argument
     *
     * @var string
     */
    protected $_charSet;

    /**
     * Corresponds to fourth htmlspecialchars() argument
     *
     * @var string
     */
    protected $_doubleEncode;

    /**
     * Ustala czy ma kodować czy dekodować tekst
     *
     * @var boolean
     */    
    protected $_encode = true;
    
    /**
     * Sets filter options
     *
     * @param  integer $quoteStyle
     * @param  string  $charSet
     * @return void
     */
    public function __construct($quoteStyle = ENT_COMPAT, $charSet = 'UTF-8', $doubleEncode = true) 
    {
        $this->_quoteStyle = $quoteStyle;
        $this->_charSet    = $charSet;
        $this->_doubleEncode = $doubleEncode;
    }

    /**
     * Returns the quoteStyle option
     *
     * @return integer
     */
    public function getQuoteStyle() 
    {
        return $this->_quoteStyle;
    }

    /**
     * Sets the quoteStyle option
     *
     * @param  integer $quoteStyle
     * @return Zend_Filter_HtmlEntities Provides a fluent interface
     */
    public function setQuoteStyle($quoteStyle) 
    {
        $this->_quoteStyle = $quoteStyle;
    }

    /**
     * Returns the charSet option
     *
     * @return string
     */
    public function getCharSet() 
    {
        return $this->_charSet;
    }

    /**
     * Sets the charSet option
     *
     * @param  string $charSet
     * @return Zend_Filter_HtmlEntities Provides a fluent interface
     */
    public function setCharSet($charSet) 
    {
        $this->_charSet = $charSet;
        return $this;
    }

    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns the string $value, converting characters to their corresponding HTML entity
     * equivalents where they exist
     *
     * @param  string $value
     * @return string
     */
    public function filter($value) 
    {
    	if ($this->_encode) {
            return htmlspecialchars((string) $value, $this->_quoteStyle, $this->_charSet, $this->_doubleEncode);
    	} else {
            return htmlspecialchars_decode((string) $value, $this->_quoteStyle);
    	}
    }
    
    
    /**
     * Ustawia czy ma kodować czy dekodować tekst przy pomocy 
     * funkcji htmlspecialchars/htmlspecialchars_decode
     *
     * @param  boolean $encode
     * @return void
     */
    public function setEncode($encode = true) 
    {
    	$this->_encode = ($encode) ? true : false;
    }
}