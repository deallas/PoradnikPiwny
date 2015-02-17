<?php

namespace WS\Session\Validator;

class Ip extends \Zend_Session_Validator_Abstract
{
    protected $ip = null;

    public function __construct()
    {
        $this->ip = WS_Security::getIp();
    }
	
    public function setup()
    {
        $this->setValidData((isset($this->ip) ? $this->ip : null) );
    }

    public function validate()
    {
        $currentIp = (isset($this->ip) ? $this->ip : null);

        return $currentIp === $this->getValidData();
    }
}