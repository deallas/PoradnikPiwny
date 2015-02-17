<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\Users\Add as AddForm,
    PoradnikPiwny\Form\Users\Edit as EditForm,
    PoradnikPiwny\Form\Users\Changepass as ChangepassForm,
    PoradnikPiwny\Security\Exception as SecurityException,
    PoradnikPiwny\Security;

class UsersController extends AdminAction
{
    public function indexAction()
    { 
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\User');  
        $options = $this->_setupOptionsPaginator($rep);  
        $paginator = $rep->getPaginator($options);

        $this->view->users = $paginator;
        $this->view->rAcl = $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole');
    }

    public function addAction()
    {
        $form = new AddForm(array(
            'roles' => $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole')
                           ->getRoleParentsForUser($this->_user),
            'themes' => $this->_themes
        ));
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {              
                $this->_em->getRepository('\PoradnikPiwny\Entities\User')
                          ->addUser($form->getValue('username'),
                                      $form->getValue('visibleName'),
                                      $form->getValue('email'), 
                                      $form->getValue('password'),
                                      $form->getValue('role'),
                                      $form->getValue('status'),
                                      $form->getValue('name'),
                                      $form->getValue('surname'),
                                      $form->getValue('theme'));
                
                $this->_helper->FlashMessenger(array('info' => 'Dodano nowego użytkownika'));              
                $this->_redirect('/users');
            } else {
                \WS\Tool::decodeFilters($form);
            }
        }

        $this->view->form = $form;
    }

    public function editAction()
    {        
        $id = $this->_getParam('id', null);
        $user = $this->_checkPermissions($id);

        $menu = $this->_navigation->findById('admin_users_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));

        $form = new EditForm(array(
            'roles' => $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole')
                                 ->getRoleParentsForUser($this->_user),
            'id' => $id,
            'themes' => $this->_themes
        ));
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {
                $this->_em->getRepository('\PoradnikPiwny\Entities\User')
                          ->editUser($user,
                                       $form->getValue('username'),
                                       $form->getValue('visibleName'),
                                       $form->getValue('email'),
                                       $form->getValue('role'),
                                       $form->getValue('status'),
                                       $form->getValue('name'),
                                       $form->getValue('surname'),
                                       $form->getValue('theme'));
                
                $this->_helper->FlashMessenger(array('success' => 'Dane użytkownika zostały zmienione'));              
                $this->_redirect('/users');
                return;
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
            \WS\Tool::decodeFilters($form, true);
            $form->populateByUser($user);
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $id = $this->_getParam('id', null);
        $user = $this->_checkPermissions($id);

        $this->_em->getRepository('\PoradnikPiwny\Entities\User')->removeUser($user);

        $this->_helper->FlashMessenger(array('success' => 'Użytkownik został usunięty'));             
        $this->_redirect('/users');
    }

    public function changepassAction()
    {
        $id = $this->_getParam('id', null);
        $user = $this->_checkPermissions($id);	

        $menu = $this->_navigation->findById('admin_users_changepass');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));

        $form = new ChangepassForm(
            array(
                'id_user' => $id
            )
        );
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {
                $this->_em->getRepository('\PoradnikPiwny\Entities\User')
                          ->changePassword($form->getValue('password'), $user);

                $this->_helper->FlashMessenger(array('success' => 'Hasło użytkownika zostało zmienione'));              
                $this->_redirect('/users');
            }
        }

        $this->view->form = $form;
    }
    
    public function infoAction()
    {
        $id = $this->_getParam('id', null);
        $user = $this->_checkPermissions($id);	
        
        $menu = $this->_navigation->findById('admin_users_info');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));
        
        $this->view->i_user = $user;
        $this->view->themes = $this->_themes;
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\User
     */
    protected function _checkPermissions($id)
    {
        if($this->_user->getId() == $id)
        {
            $this->_helper->FlashMessenger(array('warning' => 'Nie możesz usunąć samego siebie z bazy'));              
            $this->_redirect('/users');
            return;	
        }

        try {
            $user = $this->_em->getRepository('\PoradnikPiwny\Entities\User')
                              ->getUserById($id);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora użytkownika'));              
            $this->_redirect('/users');
        } catch(\PoradnikPiwny\Exception\UserNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dany użytkownik nie istnieje'));
            $this->_redirect('/users');
        }

        if(!$this->_em->getRepository('\PoradnikPiwny\Entities\AclRole')
                      ->isChildRoleForUser($user->getRole(), $this->_user))
        {
            throw new SecurityException(null, Security::ACL_PERMISSION_DENIED);
        }
        
        return $user;
    }
}