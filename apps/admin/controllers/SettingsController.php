<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\Settings\Edit as SettingsForm,
    WS\Config\Writer\IniPhp as WriterIniPhp;

class SettingsController extends AdminAction
{
    public function indexAction()
    {
        $this->view->settings = $this->_options['resources']['security'];
    }

    public function editAction()
    {
        $settings = $this->_options['resources']['security'];
        $form = new SettingsForm();
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {
                $path = APPS_CONFIG_PATH . '/security.ini.php';
                $config = new \Zend_Config_Ini($path, null, array('skipExtends' => true, 'allowModifications' => true));
                $config->blocker->enabled = $form->getValue('blocker');
                $config->blocker->time = $form->getValue('blocker_time');
                $config->blocker->attempts->max = $form->getValue('blocker_max_attempts');
                $config->blocker->attempts->time = $form->getValue('blocker_attempt_time');
                $config->recaptcha->enabled = $form->getValue('captcha');
                $config->recaptcha->min_attempts = $form->getValue('captcha_min_attempts');
                $writer = new WriterIniPhp(
                    array(
                        'config'   => $config,
                        'filename' => $path
                    )
                );
                $writer->write();

                $this->_helper->FlashMessenger(array('success' => 'Ustawienia logowania zostały zmienione'));              
                $this->_redirect('/settings');
                return;
            }
        } else {
            $form->blocker->setValue($settings['blocker']['enabled']);
            $form->blocker_max_attempts->setValue($settings['blocker']['attempts']['max']);
            $form->blocker_attempt_time->setValue($settings['blocker']['attempts']['time']);
            $form->blocker_time->setValue($settings['blocker']['time']);

            $form->captcha->setValue($settings['recaptcha']['enabled']);
            $form->captcha_min_attempts->setValue($settings['recaptcha']['min_attempts']);
        }

        $this->view->form = $form;
    }
}