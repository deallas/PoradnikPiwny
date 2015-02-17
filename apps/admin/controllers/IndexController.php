<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\Login as LoginForm,
    PoradnikPiwny\Security,
    PoradnikPiwny\Security\Exception as SecurityException,
    WS\Tool;

class IndexController extends AdminAction
{	
    public function indexAction()
    {    
        $this->view->themes = $this->_themes;
    }
    
    public function loginAction()
    {        
    	$error = $this->_getParam('exception', null);
    	$msg = null;
    	if($error instanceof \Exception) {
            $msg = $this->_checkException($error);
    	} else {
            if(!$this->_security->hasDefaultRole())
            {
                $this->_redirect301('/');
            }
    	}
        
        $this->_helper->_layout->setLayout('admin-login');
        
        $b_rep = $this->_em->getRepository('\PoradnikPiwny\Entities\BlockerAttempt');
        $b_rep->removeExpiredAttempts(Security::RESOURCE_GROUP_ADMIN);
        
    	$attempts = $b_rep->getNotExpiredAttempts(Security::RESOURCE_GROUP_ADMIN);

    	$options = $this->_security->getOptions();
    	$form = new LoginForm(
            array(
                'recaptcha' => $options['recaptcha'],
                'attempts' => $attempts
            )
    	);
    	 
    	if($this->getRequest()->isPost())
    	{
            if($form->isValid($this->getRequest()->getPost()))
            {
                try {
                    if($this->_security->login($form->getValue('username_or_email'),
                                               $form->getValue('password'),
                                               $form->getValue('remember_me'),
                                               Security::ROLE_PIWOSZ))
                    {   
                        $b_rep->removeAttempt(Security::RESOURCE_GROUP_ADMIN); 

                        $this->_helper->FlashMessenger(array('info' => 'Pomyślnie zalogowano'));              
                        $this->_redirect301('/');
                        return;
                    } else {
                        $attempts++;                  
                        if($attempts >= $options['blocker']['attempts']['max']) {
                            $msg = 'Przekroczenie ' . $options['blocker']['attempts']['max'] . ' próby logowania';
                            $date = new Zend_Date();
                            $date->addSecond($options['blocker']['time']);

                            $this->_em->getRepository('\PoradnikPiwny\Entities\BlockerRule')
                                      ->addRule(Tool::getRealIp(), $date, 1000, $msg, Security::RESOURCE_GROUP_ADMIN);

                            $b_rep->removeAttempt(Security::RESOURCE_GROUP_ADMIN);

                            $msg = $this->_translate->_('Tymczasowy ban wygasa ' . $date->toString('dd.MM.YYYY') . ' o godzinie ' . $date->toString('HH:ss'));

                            throw new SecurityException($msg, Security::BLOCKER_PERMISSION_DENIED);
                        } elseif($attempts < $options['blocker']['attempts']['max']) {
                            $date = new \Zend_Date();
                            $date->addSecond($options['blocker']['attempts']['time']);
                            $b_rep->appendAttempt(Security::RESOURCE_GROUP_ADMIN, $date);      
                        }

                        $form->setParam('attempts', $attempts);
                        $form->reloadForm();
                        $this->_helper->FlashMessenger(array('error' => 'Wprowadzono nieprawidłowy login lub/i hasło')); 
                    }
                } catch(SecurityException $exc) {
                    $msg = $this->_checkException($exc);
                }             
            } else if (count($form->getErrors('csrf')) > 0) {
                $this->_helper->FlashMessenger(array('error' => 'Wykryto atak "Cross-site request forgery" (CSRF)'));                 
            }
    	}
            
        if($msg != null)
        {
            $this->_helper->FlashMessenger(array('error' => $msg)); 
        }
        
        if($attempts != 0)
        {
            $attempts_msg = sprintf($this->_translate->_('Użyłeś %s z %s szans na zalogowanie. Po wykorzystaniu wszystkich %s szans zostaniesz zablokowany na %s'),
                $attempts,
                $options['blocker']['attempts']['max'],      
                $options['blocker']['attempts']['max'],
                $this->view->timeFormatter($options['blocker']['time'])
            );
            $this->_helper->FlashMessenger(array('warning' => $attempts_msg)); 
        }
               
    	$this->view->form = $form;
    }
    
    public function logoutAction()
    {
        if(!$this->_security->hasDefaultRole()) {
            $this->_security->logout();

            $this->_helper->FlashMessenger(array('info' => 'Pomyślnie wylogowano')); 
        }
        $this->_redirect301('/index/login');
    }
    
    /**
     * @param \Exception $exception
     * @return string
     */
    private function _checkException(\Exception $error)
    {
        $msg = null;
        
        switch($error->getCode())
        {
            case Security::ACL_PERMISSION_DENIED:
                $msg = $this->_translate->_('Brak dostępu. Proszę się zalogować'); 
                break;
            case Security::AUTH_REMOVED_ACCOUNT:
                $msg = $this->_translate->_('Konto użytkownika zostało usunięte');
                break;
            case Security::AUTH_BANNED_ACCOUNT:
                $msg = $this->_translate->_('Konto użytkownika zostało zbanowane');
                break;
            case Security::AUTH_INACTIVE_ACCOUNT:
                $msg = $this->_translate->_('Konto użytkownika nie jest aktywne');
                break;
            case Security::AUTH_STOLEN_SESSION:
                $msg = $this->_translate->_('Wykryto próbę przechwycenia sesji');
                break;
            default:
                throw new SecurityException($error->getMessage(), Security::ACL_PERMISSION_DENIED);
        }
        
        return $msg;
    }
}