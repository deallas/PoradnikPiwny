<?php

namespace PoradnikPiwny\Security;

use PoradnikPiwny\Security;

class Acl extends \Zend_Acl
{
    /**
     * @param string $role
     * @param string $resource
     * @param string $privilege
     * @return boolean
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {   
        return Security::getInstance()->isAllowed($resource, $privilege, $role) 
                    == Security::ACL_PERMISSION_GRANTED;        
    }
}
