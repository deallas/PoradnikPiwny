<?php

namespace PoradnikPiwny\Validate\Doctrine;

class ConfirmPassword extends \Zend_Validate_Abstract 
{
    const NOT_CONFIRMED = 'notConfirmed';

    protected $_messageTemplates = array
    (
        self::NOT_CONFIRMED => 'Old password is wrong'
    );
    
    protected $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public function isValid($value) 
    {
    	$this->_setValue($value);
    	$em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        if($em->getRepository('\PoradnikPiwny\Entities\User')
              ->checkPassword($value, $this->id))
        {
            return true;
        }
        
        $this->_error(self::NOT_CONFIRMED);
        return false;
    }
}