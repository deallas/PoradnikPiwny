<?php

use PoradnikPiwny\Security\Acl,
    WS\Security\Acl\Exception as AclException,
    WS\Security,
    PoradnikPiwny\Bootstrap as PPBootstrap;

class Bootstrap extends PPBootstrap
{   
    /*public function _initBlocker()
    {
        if(!defined('DOCTRINE_CLI'))
        {
            $this->bootstrap('doctrine');
            $this->bootstrap('translate');

            $em = $this->getResource('doctrine')->getEntityManager();

            $rule = $em->getRepository('\PoradnikPiwny\Entities\Blocker')
                       ->getNotExpiredRule(Acl::RESOURCE_GROUP_ADMIN);

            if($rule != null) {        
                $translate = $this->getResource('translate');

                $time_expired = $rule->getTimeExpired();
                if($time_expired == null)
                {
                    $msg = $translate->_('Gratuluje dostałeś wiecznego bana:)');
                    throw new AclException($msg, Security::BLOCKER_PERMISSION_DENIED_RESOURCE);
                } else {
                    $msg = $translate->_('Tymczasowy ban wygasa ' . $time_expired->toString('dd.MM.YYYY') . ' o godzinie ' . $time_expired->toString('HH:mm:ss'));
                    throw new AclException($msg, Security::BLOCKER_PERMISSION_DENIED_RESOURCE);			
                }
            }
        }
    }*/
}

