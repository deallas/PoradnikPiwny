<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\Account\Changepass,
    PoradnikPiwny\Form\Account\Settings;

class AccountController extends AdminAction
{
    public function changepassAction() 
    {
        $form = new Changepass(array(
            'user_id' => $this->_user->getId()
        ));
        if ($this->getRequest()->isPost()) 
        {
            if ($form->isValid($this->getRequest()->getPost())) 
            {
                $this->_em->getRepository('\PoradnikPiwny\Entities\User')
                          ->changePassword($form->getValue('password'), $this->_user);
                           
                $this->_helper->FlashMessenger(array('info' => 'Hasło zostało zmienione'));              
                $this->_redirect('/');
            }
        }
        $this->view->form = $form;
    }

    public function settingsAction() 
    {
        $form  = new Settings(array(
            'id' => $this->_user->getId(),
            'themes' => $this->_themes
        ));
        if ($this->getRequest()->isPost()) 
        {
            if ($form->isValid($this->getRequest()->getPost())) 
            {	               
                $this->_em->getRepository('\PoradnikPiwny\Entities\User')
                          ->editUser($this->_user,
                                       $form->getValue('visibleName'),
                                       null, // role
                                       null, // status
                                       $form->getValue('name'),
                                       $form->getValue('surname'),
                                       $form->getValue('theme'));
                
                $this->_helper->FlashMessenger(array('info' => 'Twoje dane zostały zaktualizowane'));              
                $this->_redirect('/');
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
            \WS\Tool::decodeFilters($form, true);
            $form->populateByUser($this->_user, $this->_userMeta);
        }
        $this->view->form = $form;
    }
}