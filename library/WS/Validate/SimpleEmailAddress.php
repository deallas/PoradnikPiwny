<?php

namespace WS\Validate;

class SimpleEmailAddress extends \Zend_Validate_EmailAddress
{
    public function getMessages() 
    {
        return array("This is not a valid e-mail address.");
    }
}
