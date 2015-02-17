<?php

namespace PoradnikPiwny\Validate\Doctrine;

class BeerManufacturerUnique extends \Zend_Validate_Abstract 
{
    const NOT_UNIQUE = 'notUnique';

    protected $_messageTemplates = array
    (
        self::NOT_UNIQUE => 'Wytwórca o danej nazwie już istnieje u danego dystrybutora'
    );
    
    /**
     * @var int
     */
    protected $_distributorId;
    
    /**
     * @var int
     */
    protected $_excludeId;
    
    /**
     * @param int $distributorId
     */
    public function __construct($distributorId, $excludeId)
    {
        $this->_distributorId = $distributorId;
        $this->_excludeId = $excludeId;
    }
    
    /**
     * @param string $value
     * @return boolean
     */
    public function isValid($value) 
    {
    	$em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        if(!$em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer')
               ->isManufacturerUnique($value, $this->_distributorId, $this->_excludeId))
        {
            return true;
        }
        
        $this->_error(self::NOT_UNIQUE);
        return false;
    }
}