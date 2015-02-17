<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\Blocker\Add as AddForm;

class BlockerController extends AdminAction
{
    public function indexAction()
    { 
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\BlockerRule');  
        $options = $this->_setupOptionsPaginator($rep);  
        $paginator = $rep->getPaginator($options);

        $this->view->rules = $paginator;
    }

    public function addAction()
    {
        $form = new AddForm(array(
            'resgroup' => $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole')
                                    ->getResgroups()
        ));

        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {
                $this->_em->getRepository('\PoradnikPiwny\Entities\BlockerRule')
                          ->addRule($form->getValue('ip'),
                                         $form->dateExpired->getDate(),
                                         $form->getValue('priority'),
                                         $form->getValue('message'),
                                         $form->getValue('resgroup')
                                );

                $this->_helper->FlashMessenger(array('info' => 'Reguła została dodana'));              
                $this->_redirect('/blocker');
                return;
            }
        }

        $this->view->form = $form;
    }

    public function editAction()
    {
        $id = $this->_getParam('id', null);
        $rule = $this->_checkBlockerRule($id);

        $menu = $this->_navigation->findById('admin_blacklist_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $id));	

        $form = new AddForm(
            array(
                'isEdit' => true,
                'id' => $id,
                'resgroup' => $this->_em->getRepository('\PoradnikPiwny\Entities\AclRole')
                                        ->getResgroups()
            )
        );

        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST)) 
            {
                $this->_em->getRepository('\PoradnikPiwny\Entities\BlockerRule')
                          ->editRule($rule,
                                     $form->getValue('ip'),
                                     $form->dateExpired->getDate(),
                                     $form->getValue('priority'),
                                     $form->getValue('message'),
                                     $form->getValue('resgroup')
                                    );
                
                $this->_helper->FlashMessenger(array('info' => 'Reguła została edytowana'));              
                $this->_redirect('/blocker');
                return;
            } else {
                \WS\Tool::decodeFilters($form);
            }
        } else {
            \WS\Tool::decodeFilters($form, 1);
            $form->populateByRule($rule);
        }

        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $id = $this->_getParam('id', null);
        $rule = $this->_checkBlockerRule($id);

        $this->_em->getRepository('\PoradnikPiwny\Entities\BlockerRule')
                  ->removeRule($rule);
        
        $this->_helper->FlashMessenger(array('info' => 'Reguła została usunięta'));              
        $this->_redirect('/blocker');
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\BlockerRule
     */
    protected function _checkBlockerRule($id)
    {
        try {
            $blocker = $this->_em->getRepository('\PoradnikPiwny\Entities\BlockerRule')
                                 ->getRuleById($id);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora reguły'));              
            $this->_redirect('/blocker');            
        } catch(PoradnikPiwny\Exception\BlockerRuleNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dana reguła nie istnieje'));
            $this->_redirect('/blocker');            
        }
        
        return $blocker;
    }
}