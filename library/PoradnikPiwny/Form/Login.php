<?php

namespace PoradnikPiwny\Form;

class Login extends \WS\Form
{
    protected $is_captcha_init = false;

    public function init()
    {	
        $this->setAttrib('id', 'form-login');
        
        $email = new \Zend_Form_Element_Text('username_or_email');
        $email->autocomplete = "off";
        $email->setFilters(array(new \Zend_Filter_StringTrim(), new \Zend_Filter_StringToLower()))
            ->setRequired(true)
            ->setLabel('Użytkownik lub email:')
            ->addValidator('NotEmpty')
            ->setAttrib('append', array('iconClass' => 'icon-envelope'));

        $password = new \Zend_Form_Element_Password('password');
        $password->setLabel('Hasło:')
            ->setRequired(true)
            ->addValidator('NotEmpty')
            ->setAttrib('append', array('iconClass' => 'icon-key'));                          

        $csrf = new \Zend_Form_Element_Hash('csrf');

        $remember_me = new \Zend_Form_Element_Checkbox('remember_me');
        $remember_me->setLabel('Zapamiętaj mnie:')
                    ->setValue(true);

        $submit = new \Twitter_Bootstrap_Form_Element_Submit('submit', 
                array(
                    'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY
               ));
        $submit->setLabel('Zaloguj');
        
        $elements = array(
            $email,
            $password,
            $remember_me,
            $csrf	
        );
        

        $captcha = $this->getCaptchaField();
        if($captcha) $elements[] = $captcha;

        $elements[] = $submit;
        $this->addElements($elements);
        
        $this->setupButtonGroup();
    }

    public function reloadForm()
    {
        $captcha = $this->getCaptchaField();
        if(!$captcha) return;

        $submit = $this->submit;
        $this->removeDisplayGroup('buttons');
        $this->addElement($captcha);
        $this->addElement($submit);
        $this->setupButtonGroup();
    }
    
    protected function setupButtonGroup()
    {
        $this->addDisplayGroup(array(
            'submit',
        ), 'buttons');
        
        $buttons = $this->getDisplayGroup('buttons');
        $buttons->setAttrib('class', 'form-actions');        
    }

    protected function getCaptchaField()
    {
        if($this->is_captcha_init) return false;

        $attempts = $this->getParam('attempts');
        $recaptcha = $this->getParam('recaptcha');

        if(!empty($recaptcha))
        {
            if(isset($recaptcha['enabled']))
            {
                if ($attempts >= $recaptcha['min_attempts'] && $recaptcha['enabled']) 
                {
                    $this->is_captcha_init = true;

                    $captcha = new \Twitter_Bootstrap_Form_Element_Captcha('captcha', array(  
                        'captcha' => 'ReCaptcha',
                        'captchaOptions' => array(  
                        'captcha' => 'ReCaptcha',  
                        'privKey' => $recaptcha['private'],
                        'pubKey' => $recaptcha['public'])
                    )); 
                    $captcha->setRequired(true)
                            ->setLabel('Captcha:')
                            ->getCaptcha()->getService()
                                ->setOption('theme',$recaptcha['theme'])
                                ->setOption('lang', $recaptcha['lang']); 

                    return $captcha;
                }
            }
        }

        return false;
    }
}