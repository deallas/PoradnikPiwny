<?php

namespace PoradnikPiwny\Validate\Doctrine;

class BlockerIpResgroupUnique extends \Zend_Validate_Abstract 
{
    const NOT_UNIQUE = 'notUnique';

    protected $_messageTemplates = array
    (
        self::NOT_UNIQUE => 'Reguła o danym adresie IP oraz przypisanym zasobie istnieje już w bazie'
    );
    
    /**
     * @var \Zend_Form_Element_Select
     */
    protected $_fieldResgroup;
    
    /**
     * @var int
     */
    protected $_excludeId;
    
    /**
     * @param \Zend_Form_Element_Select $fieldResgroup
     * @param null $excludeId
     */
    public function __construct(\Zend_Form_Element_Select $fieldResgroup, $excludeId = null)
    {
        $this->_fieldResgroup = $fieldResgroup;
        $this->_excludeId = $excludeId;
    }
    
    /**
     * @param string $value
     * @return boolean
     */
    public function isValid($value) 
    {
    	$em = \Zend_Registry::get('doctrine')->getEntityManager();
        
        if(!$em->getRepository('\PoradnikPiwny\Entities\BlockerRule')
              ->isIpResgroupUnique($value, $this->_fieldResgroup->getValue(), $this->_excludeId))
        {
            return true;
        }
        
        $this->_error(self::NOT_UNIQUE);
        return false;
    }
}