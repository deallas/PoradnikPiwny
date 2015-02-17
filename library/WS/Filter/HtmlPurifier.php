<?php

namespace WS\Filter;

class HtmlPurifier implements \Zend_Filter_Interface
{
    /**
     * @var \HTMLPurifier 
     */
    protected $_htmlPurifier;
    
    /**
     * @param array $config
     */
    public function __construct($config = null)
    {
        $this->_htmlPurifier = new \HTMLPurifier($config);
    }
 
    /**
    * @param string $value
    * @return string
    */
    public function filter($value)
    {
        return $this->_htmlPurifier->purify($value);
    }
}
