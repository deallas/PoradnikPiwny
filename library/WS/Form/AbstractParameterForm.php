<?php

namespace WS\Form;

abstract class AbstractParameterForm extends \Zend_Form 
{
    /**
     * @var array
     */
    protected $_params;
    
    /**
     * @param array $params
     * @param array $options
     */
    public function __construct(array $params = array(), $options = array())
    {
        $this->setParams($params);
        
        parent::__construct($options);
    }
    
    /**
     * @param array $params
     */
    public function setParams(array $params = array())
    {
        $this->_params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getParam($name)
    {
        return (isset($this->_params[$name])) ? $this->_params[$name] : false;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;
    }
}
