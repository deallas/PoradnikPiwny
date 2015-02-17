<?php

namespace WS;

use WS\Exception;

class Options
{
    /**
    * @var array
    */
    protected $options = array();

    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
    * @param array $options
    */
    public function setOptions($options = null)
    {
        if($options == null)
        {
            return false;
        }
        if($options instanceof \Zend_Config)
        {
            $options = $options->toArray();
        }
        if(!is_array($options))
        {
            throw new Exception('Options must be an array or a Zend_Config object');
        }

        $this->options = $options;
    }

    /**
    * @return mixed
    */
    public function getOptions()
    {
        return $this->options;
    }

    /**
    * @param string $name
    * @return mixed
    */
    public function getOption($name)
    {
        return (isset($this->options[$name])) ? $this->options[$name] : false; 
    }

    /**
    * @param string $name
    * @param mixed $value
    */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function hasOption($name)
    {
        if(isset($this->options[$name])) return true;
        return false;	
    }

    /**
    * @param mixed $name
    */
    public function removeOption($name)
    {
        if(isset($this->options[$name]))
        {
            unset($this->options[$name]);
            return true;
        } else {
            return false;
        }
    }

    public function clearOptions()
    {
        $this->options = array();
    }

    /**
    * @param string $name
    * @param mixed $value
    */
    public function __set($name, $value)
    {
        $this->setOption($name, $value);
    }

    /**
    * @param string $name
    * @return mixed
    */
    public function __get($name)
    {
        return $this->getOption($name);
    }

    /**
    * @param string $name
    */
    public function __unset($name)
    {
        return $this->removeOption($name);
    }

    /**
    * @param string $name
    */
    public function __isset($name)
    {
        return $this->hasOption($name);
    }
}